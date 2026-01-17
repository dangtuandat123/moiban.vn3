<?php

namespace App\Services;

use App\Models\Template;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use ZipArchive;

/**
 * TemplateService - Xử lý logic template thiệp
 */
class TemplateService
{
    /**
     * Lấy danh sách template active
     */
    public function getActiveTemplates()
    {
        return Template::active()
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Lấy template theo code
     */
    public function getByCode(string $code): ?Template
    {
        return Template::where('code', $code)->first();
    }

    /**
     * Import template từ file ZIP
     * 
     * Cấu trúc ZIP:
     * - view.blade.php
     * - config.json
     * - thumbnail.jpg
     * 
     * @param string $zipPath Đường dẫn file ZIP
     * @return Template
     * @throws \Exception
     */
    public function importFromZip(string $zipPath): Template
    {
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Không thể mở file ZIP');
        }

        // Tạo thư mục tạm để giải nén
        $tempDir = storage_path('app/temp/' . uniqid());
        $zip->extractTo($tempDir);
        $zip->close();

        // Validate cấu trúc
        if (!File::exists("{$tempDir}/config.json")) {
            File::deleteDirectory($tempDir);
            throw new \Exception('Thiếu file config.json');
        }

        if (!File::exists("{$tempDir}/view.blade.php")) {
            File::deleteDirectory($tempDir);
            throw new \Exception('Thiếu file view.blade.php');
        }

        // Đọc config.json
        $config = json_decode(File::get("{$tempDir}/config.json"), true);
        
        if (!isset($config['meta']['code'])) {
            File::deleteDirectory($tempDir);
            throw new \Exception('config.json thiếu meta.code');
        }

        $templateCode = $config['meta']['code'];
        $templatePath = resource_path("views/templates/{$templateCode}");

        // Tạo thư mục template
        if (!File::exists($templatePath)) {
            File::makeDirectory($templatePath, 0755, true);
        }

        // Copy files
        File::copy("{$tempDir}/view.blade.php", "{$templatePath}/view.blade.php");
        File::copy("{$tempDir}/config.json", "{$templatePath}/config.json");
        
        if (File::exists("{$tempDir}/thumbnail.jpg")) {
            File::copy("{$tempDir}/thumbnail.jpg", "{$templatePath}/thumbnail.jpg");
        }

        // Xóa thư mục tạm
        File::deleteDirectory($tempDir);

        // Tạo hoặc cập nhật trong DB
        $template = Template::updateOrCreate(
            ['code' => $templateCode],
            [
                'name' => $config['meta']['name'] ?? $templateCode,
                'description' => $config['meta']['description'] ?? null,
                'thumbnail' => 'thumbnail.jpg',
                'schema' => $config['schema'] ?? [],
                'version' => $config['meta']['version'] ?? '1.0.0',
                'is_active' => true,
            ]
        );

        Log::info('Template imported', ['code' => $templateCode]);

        return $template;
    }

    /**
     * Đồng bộ templates từ thư mục vào database
     */
    public function syncFromDirectory(): int
    {
        $templatesPath = resource_path('views/templates');
        
        if (!File::exists($templatesPath)) {
            return 0;
        }

        $count = 0;
        $directories = File::directories($templatesPath);

        foreach ($directories as $dir) {
            $configPath = "{$dir}/config.json";
            
            if (!File::exists($configPath)) {
                continue;
            }

            $config = json_decode(File::get($configPath), true);
            $code = basename($dir);

            Template::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $config['meta']['name'] ?? $code,
                    'description' => $config['meta']['description'] ?? null,
                    'thumbnail' => File::exists("{$dir}/thumbnail.jpg") ? 'thumbnail.jpg' : null,
                    'schema' => $config['schema'] ?? [],
                    'version' => $config['meta']['version'] ?? '1.0.0',
                    'is_active' => true,
                ]
            );

            $count++;
        }

        Log::info("Synced {$count} templates from directory");
        
        return $count;
    }

    /**
     * Lấy schema của template
     */
    public function getSchema(Template $template): array
    {
        return $template->schema ?? [];
    }

    /**
     * Validate content theo schema
     */
    public function validateContent(Template $template, array $content): array
    {
        $errors = [];
        $schema = $template->schema ?? [];

        foreach ($schema as $section) {
            if (!isset($section['fields'])) continue;

            foreach ($section['fields'] as $field) {
                $key = $field['key'];
                $rules = $field['rules'] ?? '';
                
                // Kiểm tra required
                if (str_contains($rules, 'required') && empty($content[$key])) {
                    $errors[$key] = "{$field['label']} là bắt buộc";
                }
            }
        }

        return $errors;
    }
}
