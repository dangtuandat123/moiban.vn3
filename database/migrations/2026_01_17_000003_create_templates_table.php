<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng templates: Kho mẫu thiệp cưới
     */
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // VD: elegant-floral
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable(); // Đường dẫn ảnh preview
            $table->json('schema')->nullable(); // config.json - định nghĩa các fields
            $table->string('version')->default('1.0.0');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_premium')->default(false); // Template trả phí
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('is_premium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
