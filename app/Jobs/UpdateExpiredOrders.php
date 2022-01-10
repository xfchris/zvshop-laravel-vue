<?php

namespace App\Jobs;

use App\Constants\AppConstants;
use App\Events\LogGeneralEvent;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateExpiredOrders implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(): void
    {
        $dateExpired = date('c', strtotime('-' . config('constants.expiration_days') . ' days'));
        $updated = Order::select('id', 'status')->where('updated_at', '<', $dateExpired)
                        ->where('status', AppConstants::PENDING)
                        ->update(['status' => AppConstants::EXPIRED]);

        LogGeneralEvent::dispatchIf($updated, 'info', 'Updated expired orders');
    }
}
