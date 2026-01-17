<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserCard;
use App\Models\Template;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * CardService - Xử lý logic thiệp cưới
 */
class CardService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Tạo thiệp mới (Trial 2 ngày)
     * 
     * @param User $user
     * @param Template $template
     * @param array $data
     * @return UserCard
     */
    public function createCard(User $user, Template $template, array $data = []): UserCard
    {
        // Merge default content từ template với data người dùng
        $defaultContent = $template->getDefaultContent();
        $content = array_merge($defaultContent, $data);

        $card = UserCard::create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'content' => $content,
            'slug' => $this->generateUniqueSlug(),
            'title' => $content['page_title'] ?? "Thiệp của {$user->name}",
            'status' => UserCard::STATUS_TRIAL,
            'trial_ends_at' => Carbon::now()->addDays(2),
        ]);

        Log::info('Card created', [
            'user_id' => $user->id,
            'card_id' => $card->id,
            'template' => $template->code,
        ]);

        return $card;
    }

    /**
     * Cập nhật nội dung thiệp
     */
    public function updateContent(UserCard $card, array $content): UserCard
    {
        $card->content = array_merge($card->content ?? [], $content);
        $card->save();

        return $card;
    }

    /**
     * Kích hoạt thiệp với gói subscription
     * 
     * @param UserCard $card
     * @param Subscription $subscription
     * @return UserCard
     * @throws \Exception
     */
    public function activateCard(UserCard $card, Subscription $subscription): UserCard
    {
        $user = $card->user;

        // Trừ tiền từ ví
        $this->walletService->withdraw(
            $user,
            $subscription->price,
            Transaction::TYPE_PURCHASE,
            "Kích hoạt thiệp: {$subscription->name}"
        );

        // Cập nhật thiệp
        $card->update([
            'subscription_id' => $subscription->id,
            'status' => UserCard::STATUS_ACTIVE,
            'subscription_ends_at' => $subscription->isPermanent() 
                ? null 
                : Carbon::now()->addDays($subscription->duration_days),
            'published_at' => Carbon::now(),
        ]);

        Log::info('Card activated', [
            'card_id' => $card->id,
            'subscription' => $subscription->name,
            'ends_at' => $card->subscription_ends_at,
        ]);

        return $card->fresh();
    }

    /**
     * Gia hạn thiệp
     */
    public function renewCard(UserCard $card, Subscription $subscription): UserCard
    {
        $user = $card->user;

        // Trừ tiền
        $this->walletService->withdraw(
            $user,
            $subscription->price,
            Transaction::TYPE_PURCHASE,
            "Gia hạn thiệp: {$subscription->name}"
        );

        // Tính ngày hết hạn mới
        $currentEndDate = $card->subscription_ends_at ?? Carbon::now();
        $newEndDate = $currentEndDate->gt(Carbon::now()) 
            ? $currentEndDate->addDays($subscription->duration_days)
            : Carbon::now()->addDays($subscription->duration_days);

        $card->update([
            'subscription_id' => $subscription->id,
            'status' => UserCard::STATUS_ACTIVE,
            'subscription_ends_at' => $subscription->isPermanent() ? null : $newEndDate,
        ]);

        return $card->fresh();
    }

    /**
     * Khóa các thiệp trial hết hạn (chạy bởi Cron)
     * 
     * @return int Số thiệp đã khóa
     */
    public function lockExpiredTrialCards(): int
    {
        $count = UserCard::expiredTrial()->update([
            'status' => UserCard::STATUS_LOCKED,
        ]);

        if ($count > 0) {
            Log::info("Locked {$count} expired trial cards");
        }

        return $count;
    }

    /**
     * Khóa các thiệp subscription hết hạn
     */
    public function lockExpiredSubscriptions(): int
    {
        $count = UserCard::where('status', UserCard::STATUS_ACTIVE)
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<', Carbon::now())
            ->update(['status' => UserCard::STATUS_EXPIRED]);

        if ($count > 0) {
            Log::info("Expired {$count} subscription cards");
        }

        return $count;
    }

    /**
     * Tạo slug unique
     */
    protected function generateUniqueSlug(): string
    {
        do {
            $slug = Str::lower(Str::random(8));
        } while (UserCard::where('slug', $slug)->exists());

        return $slug;
    }

    /**
     * Tìm thiệp theo slug
     */
    public function findBySlug(string $slug): ?UserCard
    {
        return UserCard::where('slug', $slug)->first();
    }

    /**
     * Lấy danh sách thiệp của user
     */
    public function getUserCards(User $user)
    {
        return $user->cards()
            ->with(['template', 'subscription'])
            ->orderByDesc('created_at')
            ->get();
    }
}
