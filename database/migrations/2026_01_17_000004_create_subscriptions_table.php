<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng subscriptions: Các gói dịch vụ (Basic, Premium...)
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // VD: Gói Basic, Gói Premium
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('duration_days')->default(30); // 0 = vĩnh viễn
            $table->json('features')->nullable(); // Danh sách tính năng
            $table->boolean('has_music')->default(false);
            $table->boolean('has_rsvp')->default(false);
            $table->boolean('has_guestbook')->default(false);
            $table->boolean('has_map')->default(false);
            $table->boolean('has_qr')->default(false);
            $table->boolean('remove_watermark')->default(false);
            $table->integer('max_images')->default(5);
            $table->integer('max_storage_mb')->default(50);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
