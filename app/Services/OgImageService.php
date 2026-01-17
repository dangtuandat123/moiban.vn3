<?php

namespace App\Services;

use App\Models\UserCard;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * OgImageService - Tạo ảnh OG động cho thiệp
 */
class OgImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Tạo OG Image cho thiệp
     * 
     * @param UserCard $card
     * @return string|null Path to generated image
     */
    public function generateForCard(UserCard $card): ?string
    {
        try {
            $content = $card->content ?? [];
            
            // Tạo canvas 1200x630 (chuẩn OG Image)
            $image = $this->manager->create(1200, 630);
            
            // Background gradient
            $image->fill('linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)');
            
            // Nếu có hero image, dùng làm background
            if (!empty($content['hero_image'])) {
                $heroPath = storage_path('app/public/' . $content['hero_image']);
                if (file_exists($heroPath)) {
                    $hero = $this->manager->read($heroPath);
                    $hero->cover(1200, 630);
                    $image->place($hero);
                    
                    // Overlay đen bán trong suốt
                    $overlay = $this->manager->create(1200, 630);
                    $overlay->fill('#000000');
                    $image->place($overlay, 'top-left', 0, 0, 60);
                }
            }
            
            // Text "Trân trọng kính mời"
            $image->text('Trân trọng kính mời', 600, 180, function ($font) {
                $font->file(public_path('fonts/quicksand.ttf'));
                $font->size(32);
                $font->color('#ffffff99');
                $font->align('center');
                $font->valign('middle');
            });
            
            // Tên cô dâu chú rể
            $coupleName = ($content['groom_name'] ?? 'Chú Rể') . ' & ' . ($content['bride_name'] ?? 'Cô Dâu');
            $image->text($coupleName, 600, 280, function ($font) {
                $font->file(public_path('fonts/cormorant.ttf'));
                $font->size(72);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
            });
            
            // Ngày cưới
            if (!empty($content['wedding_date'])) {
                $weddingDate = \Carbon\Carbon::parse($content['wedding_date'])->format('d.m.Y');
                $image->text($weddingDate, 600, 380, function ($font) {
                    $font->file(public_path('fonts/quicksand.ttf'));
                    $font->size(36);
                    $font->color('#D4A373');
                    $font->align('center');
                    $font->valign('middle');
                });
            }
            
            // Địa điểm
            if (!empty($content['location_name'])) {
                $image->text('📍 ' . $content['location_name'], 600, 450, function ($font) {
                    $font->file(public_path('fonts/quicksand.ttf'));
                    $font->size(24);
                    $font->color('#ffffff88');
                    $font->align('center');
                    $font->valign('middle');
                });
            }
            
            // MoiBan.vn branding (bottom right)
            $image->text('moiban.vn', 1150, 600, function ($font) {
                $font->file(public_path('fonts/quicksand.ttf'));
                $font->size(20);
                $font->color('#ffffff55');
                $font->align('right');
                $font->valign('bottom');
            });
            
            // Lưu file
            $filename = "og-images/card-{$card->id}.jpg";
            $fullPath = storage_path('app/public/' . $filename);
            
            // Tạo thư mục nếu chưa có
            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            $image->save($fullPath, quality: 85);
            
            // Cập nhật card
            $card->og_image = $filename;
            $card->save();
            
            return $filename;
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('OG Image generation failed', [
                'card_id' => $card->id,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Lấy URL OG Image hoặc fallback
     */
    public function getUrlForCard(UserCard $card): string
    {
        if ($card->og_image && Storage::disk('public')->exists($card->og_image)) {
            return asset('storage/' . $card->og_image);
        }
        
        // Fallback: hero image hoặc default
        $content = $card->content ?? [];
        if (!empty($content['hero_image'])) {
            return asset('storage/' . $content['hero_image']);
        }
        
        return asset('images/og-default.jpg');
    }
}
