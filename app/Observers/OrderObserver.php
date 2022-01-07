<?php

namespace App\Observers;

use App\Constants\AppConstants;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    public function updating(Order $order): void
    {
        if ($order->isDirty('status')) {
            if ($order->status == AppConstants::PENDING) {
                DB::select('CALL change_quantity_of_products(' . $order->id . ', false)');
            }
            if ($order->status == AppConstants::REJECTED) {
                DB::select('CALL change_orders_products_quantity(' . $order->id . ', true)');
            }
        }
    }
}
