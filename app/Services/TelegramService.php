<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * TelegramService - Gửi thông báo qua Telegram Bot
 */
class TelegramService
{
    protected ?string $botToken;
    protected ?string $chatId;

    public function __construct()
    {
        $this->botToken = Setting::get('telegram_bot_token');
        $this->chatId = Setting::get('telegram_chat_id');
    }

    /**
     * Gửi tin nhắn text
     */
    public function sendMessage(string $message): bool
    {
        if (!$this->isConfigured()) {
            Log::warning('Telegram not configured');
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram send failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Thông báo user mới đăng ký
     */
    public function notifyNewUser(string $name, string $email): bool
    {
        $message = "👤 <b>User mới đăng ký</b>\n\n";
        $message .= "Tên: {$name}\n";
        $message .= "Email: {$email}\n";
        $message .= "Thời gian: " . now()->format('d/m/Y H:i');

        return $this->sendMessage($message);
    }

    /**
     * Thông báo nạp tiền thành công
     */
    public function notifyDeposit(string $userName, float $amount, string $referenceCode): bool
    {
        $formattedAmount = number_format($amount, 0, ',', '.') . ' đ';
        
        $message = "💰 <b>Nạp tiền thành công</b>\n\n";
        $message .= "User: {$userName}\n";
        $message .= "Số tiền: {$formattedAmount}\n";
        $message .= "Mã GD: {$referenceCode}\n";
        $message .= "Thời gian: " . now()->format('d/m/Y H:i');

        return $this->sendMessage($message);
    }

    /**
     * Thông báo thiệp mới được tạo
     */
    public function notifyNewCard(string $userName, string $templateName, string $cardSlug): bool
    {
        $cardUrl = url("/c/{$cardSlug}");
        
        $message = "💒 <b>Thiệp mới được tạo</b>\n\n";
        $message .= "User: {$userName}\n";
        $message .= "Template: {$templateName}\n";
        $message .= "Link: {$cardUrl}\n";
        $message .= "Thời gian: " . now()->format('d/m/Y H:i');

        return $this->sendMessage($message);
    }

    /**
     * Thông báo lỗi hệ thống
     */
    public function notifyError(string $error, array $context = []): bool
    {
        $message = "🚨 <b>Lỗi hệ thống</b>\n\n";
        $message .= "Error: {$error}\n";
        
        if (!empty($context)) {
            $message .= "Context: " . json_encode($context, JSON_UNESCAPED_UNICODE) . "\n";
        }
        
        $message .= "Thời gian: " . now()->format('d/m/Y H:i');

        return $this->sendMessage($message);
    }

    /**
     * Kiểm tra đã cấu hình chưa
     */
    public function isConfigured(): bool
    {
        return !empty($this->botToken) && !empty($this->chatId);
    }
}
