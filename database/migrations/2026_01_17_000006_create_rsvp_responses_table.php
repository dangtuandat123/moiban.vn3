<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng rsvp_responses: Phản hồi tham dự từ khách mời
     */
    public function up(): void
    {
        Schema::create('rsvp_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_card_id')->constrained('user_cards')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('attendance', ['yes', 'no', 'maybe'])->default('yes');
            $table->integer('guest_count')->default(1); // Số người đi cùng
            $table->text('message')->nullable(); // Lời nhắn
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->index('user_card_id');
            $table->index('attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsvp_responses');
    }
};
