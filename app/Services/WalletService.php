<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * WalletService - Xử lý logic ví và giao dịch
 * 
 * Tuân thủ Service Pattern: Tất cả business logic trong Service
 */
class WalletService
{
    /**
     * Nạp tiền vào ví
     * 
     * @param User $user
     * @param float $amount
     * @param string|null $referenceCode VD: MOIBAN+123+NAP
     * @param string|null $description
     * @return Transaction
     */
    public function deposit(User $user, float $amount, ?string $referenceCode = null, ?string $description = null): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $referenceCode, $description) {
            $wallet = $user->getOrCreateWallet();
            
            $balanceBefore = $wallet->balance;
            $wallet->increment('balance', $amount);
            
            $transaction = Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => Transaction::TYPE_DEPOSIT,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
                'reference_code' => $referenceCode,
                'description' => $description ?? 'Nạp tiền vào ví',
            ]);

            Log::info('Wallet deposit', [
                'user_id' => $user->id,
                'amount' => $amount,
                'new_balance' => $wallet->balance,
                'reference_code' => $referenceCode,
            ]);

            return $transaction;
        });
    }

    /**
     * Trừ tiền từ ví (mua gói, thanh toán)
     * 
     * @param User $user
     * @param float $amount
     * @param string $type
     * @param string|null $description
     * @return Transaction
     * @throws \Exception
     */
    public function withdraw(User $user, float $amount, string $type = Transaction::TYPE_WITHDRAW, ?string $description = null): Transaction
    {
        return DB::transaction(function () use ($user, $amount, $type, $description) {
            $wallet = $user->getOrCreateWallet();
            
            if (!$wallet->hasSufficientBalance($amount)) {
                throw new \Exception('Số dư không đủ. Vui lòng nạp thêm tiền.');
            }
            
            $balanceBefore = $wallet->balance;
            $wallet->decrement('balance', $amount);
            
            $transaction = Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => $type,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $wallet->balance,
                'description' => $description ?? 'Thanh toán',
            ]);

            Log::info('Wallet withdraw', [
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => $type,
                'new_balance' => $wallet->balance,
            ]);

            return $transaction;
        });
    }

    /**
     * Hoàn tiền
     */
    public function refund(User $user, float $amount, ?string $description = null): Transaction
    {
        return $this->deposit($user, $amount, null, $description ?? 'Hoàn tiền');
    }

    /**
     * Lấy số dư ví
     */
    public function getBalance(User $user): float
    {
        return $user->wallet?->balance ?? 0;
    }

    /**
     * Lấy lịch sử giao dịch
     */
    public function getTransactionHistory(User $user, int $limit = 20)
    {
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return collect();
        }

        return $wallet->transactions()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Xử lý callback từ VietQR
     * Format: MOIBAN+{userId}+NAP
     * 
     * @param string $referenceCode
     * @param float $amount
     * @return Transaction|null
     */
    public function processVietQRCallback(string $referenceCode, float $amount): ?Transaction
    {
        // Parse reference code
        if (!preg_match('/^MOIBAN\+(\d+)\+NAP$/i', $referenceCode, $matches)) {
            Log::warning('Invalid VietQR reference code', ['code' => $referenceCode]);
            return null;
        }

        $userId = $matches[1];
        $user = User::find($userId);

        if (!$user) {
            Log::warning('User not found for VietQR callback', ['user_id' => $userId]);
            return null;
        }

        // Kiểm tra giao dịch trùng
        $existingTransaction = Transaction::where('reference_code', $referenceCode)
            ->where('amount', $amount)
            ->first();

        if ($existingTransaction) {
            Log::warning('Duplicate VietQR transaction', ['code' => $referenceCode]);
            return null;
        }

        return $this->deposit($user, $amount, $referenceCode, 'Nạp tiền qua VietQR');
    }
}
