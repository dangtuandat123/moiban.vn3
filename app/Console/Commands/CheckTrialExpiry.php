<?php

namespace App\Console\Commands;

use App\Services\CardService;
use Illuminate\Console\Command;

/**
 * Cron Job: Kiểm tra và khóa các thiệp trial hết hạn
 * Schedule: Chạy mỗi giờ
 */
class CheckTrialExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cards:check-trial-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Khóa các thiệp trial đã hết hạn 2 ngày dùng thử';

    /**
     * Execute the console command.
     */
    public function handle(CardService $cardService): int
    {
        $this->info('Đang kiểm tra thiệp trial hết hạn...');

        // Khóa trial hết hạn
        $trialLocked = $cardService->lockExpiredTrialCards();
        $this->info("Đã khóa {$trialLocked} thiệp trial hết hạn.");

        // Khóa subscription hết hạn
        $subExpired = $cardService->lockExpiredSubscriptions();
        $this->info("Đã đánh dấu {$subExpired} thiệp subscription hết hạn.");

        return Command::SUCCESS;
    }
}
