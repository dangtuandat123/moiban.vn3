<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'is_active',
        'bank_info',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'bank_info' => 'array',
        'last_login_at' => 'datetime',
    ];

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    /**
     * Ví của user
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Các thiệp của user
     */
    public function cards(): HasMany
    {
        return $this->hasMany(UserCard::class);
    }

    /**
     * Kiểm tra user có phải admin không
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Lấy hoặc tạo ví cho user
     */
    public function getOrCreateWallet(): Wallet
    {
        if (!$this->wallet) {
            return $this->wallet()->create(['balance' => 0]);
        }
        return $this->wallet;
    }

    /**
     * Lấy avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset("storage/avatars/{$this->avatar}");
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    /**
     * Lấy số dư ví
     */
    public function getBalanceAttribute(): float
    {
        return $this->wallet?->balance ?? 0;
    }
}
