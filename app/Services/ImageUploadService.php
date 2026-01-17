<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

/**
 * ImageUploadService - Xử lý upload và resize ảnh
 */
class ImageUploadService
{
    protected ImageManager $manager;
    protected int $maxWidth = 1920;
    protected int $maxHeight = 1080;
    protected int $thumbnailSize = 400;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Upload và resize ảnh
     * 
     * @param UploadedFile $file
     * @param string $directory
     * @param bool $createThumbnail
     * @return array ['path' => string, 'thumbnail' => string|null]
     */
    public function upload(UploadedFile $file, string $directory, bool $createThumbnail = false): array
    {
        // Đọc ảnh
        $image = $this->manager->read($file->getRealPath());
        
        // Resize nếu quá lớn (giữ tỷ lệ)
        if ($image->width() > $this->maxWidth || $image->height() > $this->maxHeight) {
            $image->scaleDown($this->maxWidth, $this->maxHeight);
        }
        
        // Tạo filename unique
        $filename = time() . '_' . uniqid() . '.jpg';
        $path = $directory . '/' . $filename;
        
        // Lưu ảnh chính
        $fullPath = storage_path('app/public/' . $path);
        $this->ensureDirectory(dirname($fullPath));
        $image->save($fullPath, quality: 85);
        
        $result = ['path' => $path, 'thumbnail' => null];
        
        // Tạo thumbnail nếu cần
        if ($createThumbnail) {
            $thumbnailFilename = 'thumb_' . $filename;
            $thumbnailPath = $directory . '/thumbnails/' . $thumbnailFilename;
            $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
            
            $this->ensureDirectory(dirname($thumbnailFullPath));
            
            $thumbnail = $this->manager->read($file->getRealPath());
            $thumbnail->cover($this->thumbnailSize, $this->thumbnailSize);
            $thumbnail->save($thumbnailFullPath, quality: 80);
            
            $result['thumbnail'] = $thumbnailPath;
        }
        
        return $result;
    }

    /**
     * Upload nhiều ảnh
     * 
     * @param array $files
     * @param string $directory
     * @return array
     */
    public function uploadMultiple(array $files, string $directory): array
    {
        $results = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $results[] = $this->upload($file, $directory, true);
            }
        }
        
        return $results;
    }

    /**
     * Thêm watermark vào ảnh
     * 
     * @param string $imagePath Path trong storage
     * @param string $text
     * @return bool
     */
    public function addWatermark(string $imagePath, string $text = 'moiban.vn'): bool
    {
        try {
            $fullPath = storage_path('app/public/' . $imagePath);
            
            if (!file_exists($fullPath)) {
                return false;
            }
            
            $image = $this->manager->read($fullPath);
            
            // Thêm watermark text góc phải dưới
            $image->text($text, $image->width() - 20, $image->height() - 20, function ($font) {
                $font->file(public_path('fonts/quicksand.ttf'));
                $font->size(24);
                $font->color('#ffffff55');
                $font->align('right');
                $font->valign('bottom');
            });
            
            $image->save($fullPath, quality: 90);
            
            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Xóa ảnh
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Đảm bảo thư mục tồn tại
     */
    protected function ensureDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    /**
     * Set max dimensions
     */
    public function setMaxDimensions(int $width, int $height): self
    {
        $this->maxWidth = $width;
        $this->maxHeight = $height;
        return $this;
    }
}
