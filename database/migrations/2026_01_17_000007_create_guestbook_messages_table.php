<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng guestbook_messages: Lời chúc từ khách mời
     */
    public function up(): void
    {
        Schema::create('guestbook_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_card_id')->constrained('user_cards')->cascadeOnDelete();
            $table->string('name');
            $table->text('message');
            $table->boolean('is_approved')->default(true); // Duyệt hiển thị
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            $table->index(['user_card_id', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guestbook_messages');
    }
};
