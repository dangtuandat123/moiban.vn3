<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model RsvpResponse - Phản hồi tham dự từ khách mời
 */
class RsvpResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_card_id',
        'name',
        'phone',
        'email',
        'attendance',
        'guest_count',
        'message',
        'ip_address',
    ];

    const ATTENDANCE_YES = 'yes';
    const ATTENDANCE_NO = 'no';
    const ATTENDANCE_MAYBE = 'maybe';

    /**
     * Quan hệ với UserCard
     */
    public function userCard(): BelongsTo
    {
        return $this->belongsTo(UserCard::class);
    }

    /**
     * Scope lọc theo attendance
     */
    public function scopeAttending($query)
    {
        return $query->where('attendance', self::ATTENDANCE_YES);
    }

    /**
     * Lấy text hiển thị attendance
     */
    public function getAttendanceTextAttribute(): string
    {
        return match($this->attendance) {
            self::ATTENDANCE_YES => '✅ Sẽ tham dự',
            self::ATTENDANCE_NO => '❌ Không tham dự',
            self::ATTENDANCE_MAYBE => '🤔 Chưa chắc chắn',
            default => 'Không rõ',
        };
    }
}
