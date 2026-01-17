<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Transaction - Lịch sử giao dịch ví
 * 
 * @property int $id
 * @property int $wallet_id
 * @property string $type
 * @property float $amount
 * @property float $balance_before
 * @property float $balance_after
 * @property string|null $description
 * @property string|null $reference_code
 * @property array|null $metadata
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference_code',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Các loại giao dịch
     */
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';
    const TYPE_PURCHASE = 'purchase';
    const TYPE_REFUND = 'refund';

    /**
     * Quan hệ với Wallet
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Lấy user thông qua wallet
     */
    public function user()
    {
        return $this->wallet->user;
    }

    /**
     * Scope lọc theo type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Format số tiền hiển thị
     */
    public function getFormattedAmountAttribute(): string
    {
        $prefix = in_array($this->type, [self::TYPE_DEPOSIT, self::TYPE_REFUND]) ? '+' : '-';
        return $prefix . number_format($this->amount, 0, ',', '.') . ' đ';
    }
}
