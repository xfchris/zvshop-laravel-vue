<?php

namespace App\Console\Commands;

use App\Constants\AppConstants;
use App\Events\LogGeneralEvent;
use App\Models\Order;
use Illuminate\Console\Command;

class UpdateExpiredOrders extends Command
{
    protected $signature = 'update:expired_orders';
    protected $description = 'Update expired orders';

    public function handle(): int
    {
        $dateExpired = now()->subDays(config('constants.expiration_days'))->format('c');
        $updated = Order::where('updated_at', '<', $dateExpired)
                        ->where('status', AppConstants::PENDING)
                        ->update(['status' => AppConstants::EXPIRED]);

        LogGeneralEvent::dispatchIf($updated, 'info', 'Updated expired orders');
        return Command::SUCCESS;
    }
}
