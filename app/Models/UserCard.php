<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Model UserCard - Thiệp của user (Core model)
 * 
 * @property int $id
 * @property int $user_id
 * @property int $template_id
 * @property int|null $subscription_id
 * @property array|null $content
 * @property string $slug
 * @property string|null $title
 * @property string $status
 * @property Carbon|null $trial_ends_at
 * @property Carbon|null $subscription_ends_at
 * @property Carbon|null $published_at
 * @property int $view_count
 */
class UserCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'subscription_id',
        'content',
        'slug',
        'title',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
        'published_at',
        'view_count',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    protected $casts = [
        'content' => 'array',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    /**
     * Các trạng thái
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_TRIAL = 'trial';
    const STATUS_ACTIVE = 'active';
    const STATUS_LOCKED = 'locked';
    const STATUS_EXPIRED = 'expired';

    /**
     * Tự động tạo slug khi tạo mới
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::random(8);
            }
            // Mặc định trial 2 ngày
            if (empty($model->status)) {
                $model->status = self::STATUS_TRIAL;
                $model->trial_ends_at = Carbon::now()->addDays(2);
            }
        });
    }

    /**
     * Quan hệ với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với Template
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Quan hệ với Subscription
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Các RSVP responses
     */
    public function rsvpResponses(): HasMany
    {
        return $this->hasMany(RsvpResponse::class);
    }

    /**
     * Các lời chúc
     */
    public function guestbookMessages(): HasMany
    {
        return $this->hasMany(GuestbookMessage::class);
    }

    /**
     * Scope lọc theo trạng thái
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope thiệp đang trial và hết hạn
     */
    public function scopeExpiredTrial($query)
    {
        return $query->where('status', self::STATUS_TRIAL)
                     ->where('trial_ends_at', '<', Carbon::now());
    }

    /**
     * Kiểm tra thiệp có đang active không
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Kiểm tra thiệp có bị khóa không
     */
    public function isLocked(): bool
    {
        return in_array($this->status, [self::STATUS_LOCKED, self::STATUS_EXPIRED]);
    }

    /**
     * Kiểm tra có cần hiển thị watermark không
     */
    public function shouldShowWatermark(): bool
    {
        if ($this->status === self::STATUS_TRIAL) {
            return true;
        }
        
        if ($this->subscription && $this->subscription->remove_watermark) {
            return false;
        }
        
        return true;
    }

    /**
     * Lấy URL public của thiệp
     */
    public function getPublicUrlAttribute(): string
    {
        return url("/c/{$this->slug}");
    }

    /**
     * Lấy URL OG Image
     */
    public function getOgImageUrlAttribute(): string
    {
        return url("/card/{$this->id}/og-image");
    }

    /**
     * Lấy giá trị content field
     */
    public function getContentValue(string $key, $default = null)
    {
        return data_get($this->content, $key, $default);
    }

    /**
     * Tăng view count
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Lấy tên cô dâu chú rể từ content
     */
    public function getCoupleNamesAttribute(): string
    {
        $groom = $this->getContentValue('groom_name', 'Chú rể');
        $bride = $this->getContentValue('bride_name', 'Cô dâu');
        return "{$groom} & {$bride}";
    }
}
