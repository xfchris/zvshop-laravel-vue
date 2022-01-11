<?php

namespace App\Console;

use App\Console\Commands\UpdateExpiredOrders;
use App\Jobs\UpdateStatusPayments;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        UpdateExpiredOrders::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new UpdateStatusPayments())->everyMinute()->withoutOverlapping();
        $schedule->command('update:expired_orders')->everyMinute()->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
