<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Model Setting - Cấu hình hệ thống
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * Lấy giá trị setting
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::remember("setting.{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set giá trị setting
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'group' => $group,
            ]
        );

        Cache::forget("setting.{$key}");
    }

    /**
     * Cast value theo type
     */
    protected static function castValue($value, string $type)
    {
        return match($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'json', 'array' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Lấy tất cả settings theo group
     */
    public static function getByGroup(string $group): array
    {
        $settings = self::where('group', $group)->get();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = self::castValue($setting->value, $setting->type);
        }
        
        return $result;
    }
}
