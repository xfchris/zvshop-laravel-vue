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
        $updated = [];

        foreach ($orders as $order) {
            $status = $paymentGateway->getStatus($order->payment->requestId)->status;
            if (in_array($status, AppConstants::STATUS) && $order->status != $status) {
                $order->update(['status' => $status]);
                $updated[] = $order->id;
            }
        }
        LogGeneralEvent::dispatchIf($orders->count(), 'info', 'Update status orders, order ids updated: ' . json_encode($updated));
    }
}
