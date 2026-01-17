<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model GuestbookMessage - Lời chúc từ khách mời
 */
class GuestbookMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_card_id',
        'name',
        'message',
        'is_approved',
        'ip_address',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    /**
     * Quan hệ với UserCard
     */
    public function userCard(): BelongsTo
    {
        return $this->belongsTo(UserCard::class);
    }

    /**
     * Scope lọc lời chúc đã duyệt
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope lọc lời chúc chờ duyệt
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }
}
