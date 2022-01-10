<?php

namespace App\Services\Order;

use App\Constants\AppConstants;
use App\Factories\PaymentGateway\Contracts\PaymentGatewayContract;
use App\Factories\PaymentGateway\Responses\PaymentResponse;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function __construct(
        public PaymentGatewayContract $paymentGateway
    ) {
    }
    public function getOrderByUser(): Order
    {
        return Auth::user()->order;
    }

    public function pay(?Order $order = null): PaymentResponse
    {
        $order = $order ?? $this->getOrderByUser();
        $response = $this->paymentGateway->pay($order);

        if ($response->statusResponse->status == AppConstants::CREATED) {
            $order->payments()->create([
                'requestId' => $response->requestId,
                'processUrl' => $response->processUrl,
                'status' => $response->statusResponse->status,
                'products' => $order->lastProductsCopy(),
                'totalAmount' => $order->totalAmount,
                'reference_id' => $order->referencePayment,
            ]);
        }
        return $response;
    }

    public function changeStatus(string $referenceId): array
    {
        $payment = Payment::where('reference_id', $referenceId)->latest()->first();

        if ($payment->order->status == AppConstants::PENDING) {
            $response = $this->paymentGateway->getStatus($payment->requestId);
            $payment->update(['status' => $response->status]);
            return [$payment->order->id, $response->status];
        }
        return [$payment->order->id, $payment->order->status];
    }

    public function showUserOrders(): LengthAwarePaginator
    {
        return Auth::user()->orders()->where('status', '!=', AppConstants::CREATED)->orderBy('id', 'DESC')
                ->paginate(config('constants.num_rows_per_table'));
    }
}
