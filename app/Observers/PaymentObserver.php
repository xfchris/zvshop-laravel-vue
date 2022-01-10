<?php

namespace App\Observers;

use App\Constants\AppConstants;
use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
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
