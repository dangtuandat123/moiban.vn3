<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model Subscription - Các gói dịch vụ
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property float $price
 * @property int $duration_days
 * @property array|null $features
 * @property bool $has_music
 * @property bool $has_rsvp
 * @property bool $has_guestbook
 * @property bool $has_map
 * @property bool $has_qr
 * @property bool $remove_watermark
 * @property int $max_images
 * @property int $max_storage_mb
 */
class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration_days',
        'features',
        'has_music',
        'has_rsvp',
        'has_guestbook',
        'has_map',
        'has_qr',
        'remove_watermark',
        'max_images',
        'max_storage_mb',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'has_music' => 'boolean',
        'has_rsvp' => 'boolean',
        'has_guestbook' => 'boolean',
        'has_map' => 'boolean',
        'has_qr' => 'boolean',
        'remove_watermark' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Tự động tạo slug từ name
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Các thiệp sử dụng gói này
     */
    public function userCards(): HasMany
    {
        return $this->hasMany(UserCard::class);
    }

    /**
     * Scope lọc gói active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Kiểm tra gói có vĩnh viễn không
     */
    public function isPermanent(): bool
    {
        return $this->duration_days === 0;
    }

    /**
     * Format giá hiển thị
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', '.') . ' đ';
    }

    /**
     * Lấy danh sách tính năng để hiển thị
     */
    public function getFeatureListAttribute(): array
    {
        $list = [];
        
        if ($this->has_music) $list[] = '🎵 Nhạc nền';
        if ($this->has_rsvp) $list[] = '✉️ RSVP Form';
        if ($this->has_guestbook) $list[] = '📝 Lời chúc';
        if ($this->has_map) $list[] = '📍 Google Maps';
        if ($this->has_qr) $list[] = '💝 VietQR Mừng cưới';
        if ($this->remove_watermark) $list[] = '✨ Không watermark';
        
        $list[] = "🖼️ Tối đa {$this->max_images} ảnh";
        $list[] = "💾 {$this->max_storage_mb}MB dung lượng";
        
        if ($this->isPermanent()) {
            $list[] = '♾️ Vĩnh viễn';
        } else {
            $list[] = "📅 {$this->duration_days} ngày";
        }
        
        return $list;
    }
}
