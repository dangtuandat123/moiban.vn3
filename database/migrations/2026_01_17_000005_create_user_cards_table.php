<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng user_cards: Thiệp của user (core table)
     */
    public function up(): void
    {
        Schema::create('user_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_id')->constrained()->restrictOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            
            // Content JSON chứa tất cả dữ liệu của thiệp
            $table->json('content')->nullable();
            
            // Thông tin định danh
            $table->string('slug')->unique(); // URL: moiban.vn/c/slug
            $table->string('title')->nullable(); // Tiêu đề thiệp
            
            // Trạng thái
            $table->enum('status', ['draft', 'trial', 'active', 'locked', 'expired'])->default('draft');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->timestamp('published_at')->nullable();
            
            // Thống kê
            $table->integer('view_count')->default(0);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            
            $table->timestamps();
            
            $table->index('status');
            $table->index('trial_ends_at');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cards');
    }
};
