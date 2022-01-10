<?php

namespace App\Observers;

use App\Constants\AppConstants;
use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    public function updating(Order $order): void
    {
        if ($order->isDirty('status')) {
            switch ($order->status) {
                case AppConstants::PENDING:
                    DB::select('CALL change_quantity_of_products(' . $order->id . ', false)');
                    break;
                case AppConstants::REJECTED:
                    DB::select('CALL change_quantity_of_products(' . $order->id . ', true)');
                    $order->user->notify(new OrderStatusChangedNotification($order));
                    break;
                case AppConstants::APPROVED:
                    $order->user->notify(new OrderStatusChangedNotification($order));
                    break;
                case AppConstants::EXPIRED:
                    $order->user->notify(new OrderStatusChangedNotification($order));
                    break;
                default:
            }
        }
    }
}
