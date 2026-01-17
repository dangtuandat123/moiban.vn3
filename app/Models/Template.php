<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Template - Kho mẫu thiệp cưới
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string|null $thumbnail
 * @property array|null $schema
 * @property string $version
 * @property bool $is_active
 * @property bool $is_premium
 * @property int $sort_order
 */
class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'thumbnail',
        'schema',
        'version',
        'is_active',
        'is_premium',
        'sort_order',
    ];

    protected $casts = [
        'schema' => 'array',
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
    ];

    /**
     * Các thiệp sử dụng template này
     */
    public function userCards(): HasMany
    {
        return $this->hasMany(UserCard::class);
    }

    /**
     * Scope lọc template active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope lọc template premium
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope lọc template miễn phí
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Lấy đường dẫn view blade của template
     */
    public function getViewPathAttribute(): string
    {
        return "templates.{$this->code}.view";
    }

    /**
     * Lấy thumbnail URL
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset("storage/templates/{$this->code}/{$this->thumbnail}");
        }
        return asset('images/default-template.jpg');
    }

    /**
     * Lấy schema sections
     */
    public function getSchemaSections(): array
    {
        return $this->schema ?? [];
    }

    /**
     * Lấy default values từ schema
     */
    public function getDefaultContent(): array
    {
        $defaults = [];
        $sections = $this->schema ?? [];
        
        foreach ($sections as $section) {
            if (isset($section['fields'])) {
                foreach ($section['fields'] as $field) {
                    $defaults[$field['key']] = $field['default'] ?? null;
                }
            }
        }
        
        return $defaults;
    }
}
