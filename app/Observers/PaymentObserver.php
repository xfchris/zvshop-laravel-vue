<?php

namespace App\Observers;

use App\Constants\AppConstants;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        Log::debug('debug: ' . json_encode($payment));
        if ($payment->status == AppConstants::CREATED) {
            $payment->order->update(['status' => AppConstants::PENDING]);
        }
    }

    public function updated(Payment $payment): void
    {
        if (in_array($payment->status, [AppConstants::APPROVED, AppConstants::REJECTED, AppConstants::EXPIRED])) {
            $payment->order->update(['status' => $payment->status]);
        }
    }
}
