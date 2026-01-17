<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Models\Subscription;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo Subscriptions (Gói dịch vụ)
        Subscription::create([
            'name' => 'Gói Basic',
            'slug' => 'basic',
            'description' => 'Thiệp cơ bản, phù hợp cho đám cưới đơn giản',
            'price' => 99000,
            'duration_days' => 30,
            'has_music' => false,
            'has_rsvp' => false,
            'has_guestbook' => false,
            'has_map' => true,
            'has_qr' => false,
            'remove_watermark' => false,
            'max_images' => 5,
            'max_storage_mb' => 50,
            'sort_order' => 1,
        ]);

        Subscription::create([
            'name' => 'Gói Premium',
            'slug' => 'premium',
            'description' => 'Full tính năng, vĩnh viễn',
            'price' => 199000,
            'duration_days' => 0, // Vĩnh viễn
            'has_music' => true,
            'has_rsvp' => true,
            'has_guestbook' => true,
            'has_map' => true,
            'has_qr' => true,
            'remove_watermark' => true,
            'max_images' => 20,
            'max_storage_mb' => 200,
            'sort_order' => 2,
        ]);

        // Tạo Template mẫu từ config.json
        $templatePath = resource_path('views/templates/elegant-floral/config.json');
        if (file_exists($templatePath)) {
            $config = json_decode(file_get_contents($templatePath), true);
            
            Template::create([
                'code' => $config['meta']['code'] ?? 'elegant-floral',
                'name' => $config['meta']['name'] ?? 'Elegant Floral',
                'description' => $config['meta']['description'] ?? null,
                'thumbnail' => 'thumbnail.jpg',
                'schema' => $config['schema'] ?? [],
                'version' => $config['meta']['version'] ?? '1.0.0',
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 1,
            ]);
        }

        // Tạo Settings mặc định
        $defaultSettings = [
            // General
            ['key' => 'site_name', 'value' => 'MoiBan.vn', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Tạo thiệp cưới online đẹp, miễn phí', 'type' => 'string', 'group' => 'general'],
            
            // Payment
            ['key' => 'bank_name', 'value' => 'ACB', 'type' => 'string', 'group' => 'payment'],
            ['key' => 'bank_account', 'value' => '11183041', 'type' => 'string', 'group' => 'payment'],
            ['key' => 'bank_holder', 'value' => 'DANG TUAN DAT', 'type' => 'string', 'group' => 'payment'],
            ['key' => 'payment_api_token', 'value' => '', 'type' => 'string', 'group' => 'payment'],
            
            // Telegram
            ['key' => 'telegram_bot_token', 'value' => '', 'type' => 'string', 'group' => 'telegram'],
            ['key' => 'telegram_chat_id', 'value' => '', 'type' => 'string', 'group' => 'telegram'],
            
            // SEO
            ['key' => 'seo_title', 'value' => 'MoiBan.vn - Thiệp cưới online', 'type' => 'string', 'group' => 'seo'],
            ['key' => 'seo_description', 'value' => 'Tạo thiệp cưới online đẹp, chuyên nghiệp trong vài phút', 'type' => 'string', 'group' => 'seo'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::create($setting);
        }

        $this->command->info('✅ Đã seed: 2 Subscriptions, 1 Template, Settings mặc định');
    }
}
