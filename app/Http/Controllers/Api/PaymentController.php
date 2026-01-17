<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * PaymentController - API xử lý callback từ VietQR/Bank
 */
class PaymentController extends Controller
{
    protected WalletService $walletService;
    protected TelegramService $telegramService;

    public function __construct(WalletService $walletService, TelegramService $telegramService)
    {
        $this->walletService = $walletService;
        $this->telegramService = $telegramService;
    }

    /**
     * Callback từ ngân hàng/VietQR
     * POST /api/payment/callback
     * 
     * Expected payload:
     * {
     *   "amount": 100000,
     *   "description": "MOIBAN+123+NAP",
     *   "token": "secure_token_here"
     * }
     */
    public function callback(Request $request)
    {
        Log::info('Payment callback received', $request->all());

        // Verify token
        $expectedToken = \App\Models\Setting::get('payment_api_token');
        
        if (empty($expectedToken) || $request->input('token') !== $expectedToken) {
            Log::warning('Invalid payment callback token');
            return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
        }

        // Lấy thông tin giao dịch
        $amount = floatval($request->input('amount', 0));
        $description = $request->input('description', '');

        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid amount'], 400);
        }

        // Xử lý nạp tiền
        $transaction = $this->walletService->processVietQRCallback($description, $amount);

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Could not process transaction'], 400);
        }

        // Gửi thông báo Telegram
        $user = $transaction->wallet->user;
        $this->telegramService->notifyDeposit(
            $user->name,
            $amount,
            $description
        );

        return response()->json([
            'success' => true,
            'message' => 'Transaction processed',
            'transaction_id' => $transaction->id
        ]);
    }

    /**
     * Lấy thông tin VietQR để hiển thị
     * GET /api/payment/qr-info
     */
    public function qrInfo()
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        $bankName = \App\Models\Setting::get('bank_name', 'ACB');
        $bankAccount = \App\Models\Setting::get('bank_account');
        $bankHolder = \App\Models\Setting::get('bank_holder');
        $message = "MOIBAN+{$user->id}+NAP";

        // VietQR URL format
        $qrUrl = "https://img.vietqr.io/image/{$bankName}-{$bankAccount}-compact.png"
            . "?accountName=" . urlencode($bankHolder)
            . "&addInfo=" . urlencode($message);

        return response()->json([
            'success' => true,
            'data' => [
                'bank_name' => $bankName,
                'bank_account' => $bankAccount,
                'bank_holder' => $bankHolder,
                'message' => $message,
                'qr_url' => $qrUrl,
            ]
        ]);
    }
}
