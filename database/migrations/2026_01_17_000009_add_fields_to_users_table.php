<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm các trường cho bảng users
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->enum('role', ['user', 'admin'])->default('user')->after('avatar');
            $table->boolean('is_active')->default(true)->after('role');
            $table->json('bank_info')->nullable()->after('is_active'); // Thông tin ngân hàng cho VietQR
            $table->timestamp('last_login_at')->nullable();
            
            $table->index('phone');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'role', 'is_active', 'bank_info', 'last_login_at']);
        });
    }
};
