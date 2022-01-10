<?php

namespace App\Jobs;

use App\Constants\AppConstants;
use App\Events\LogGeneralEvent;
use App\Factories\PaymentGateway\Contracts\PaymentGatewayContract;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStatusPayments implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(PaymentGatewayContract $paymentGateway): void
    {
        $orders = Order::where('status', AppConstants::PENDING)->get();

        foreach ($orders as $order) {
            $status = $paymentGateway->getStatus($order->payment->requestId)->status;
            if ($order->status != $status) {
                $order->update(['status' => $status]);
            }
        }
        LogGeneralEvent::dispatchIf($orders->count(), 'info', 'Updated status orders');
    }
}
