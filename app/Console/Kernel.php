<?php

namespace App\Console;

use App\Jobs\UpdateExpiredOrders;
use App\Jobs\UpdateStatusPayments;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new UpdateStatusPayments())->everyFiveMinutes()->withoutOverlapping();
        $schedule->job(new UpdateExpiredOrders())->hourly()->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
