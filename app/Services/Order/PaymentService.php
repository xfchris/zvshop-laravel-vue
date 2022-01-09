<?php

namespace App\Services\Order;

use App\Constants\AppConstants;
use App\Factories\PaymentGateway\PaymentGateway;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function __construct(
        public PaymentGateway $paymentGateway
    ) {
    }
    public function getOrderByUser(): Order
    {
        return Auth::user()->order;
    }

    public function pay(): ?string
    {
        $order = $this->getOrderByUser();
        $response = $this->paymentGateway->pay($order);

        if ($response->statusResponse->status == AppConstants::CREATED) {
            $order->payments()->create([
                'requestId' => $response->requestId,
                'processUrl' => $response->processUrl,
                'status' => $response->statusResponse->status,
                'products' => $order->products->makeHidden(['id', 'poster', 'quantity'])->toArray(),
                'totalAmount' => $order->totalAmount,
                'reference_id' => $order->referencePayment,
            ]);
            return $response->processUrl;
        }
        return null;
    }

    public function showUserOrders(): Collection
    {
        return Auth::user()->orders()->where('status', '!=', AppConstants::CREATED)->get();
    }
}
