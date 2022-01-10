<?php

namespace App\Factories\PaymentGateway;

use App\Constants\AppConstants;
use App\Events\LogGeneralEvent;
use App\Factories\PaymentGateway\Contracts\PaymentGatewayContract;
use App\Factories\PaymentGateway\Responses\PaymentResponse;
use App\Factories\PaymentGateway\Responses\StatusResponse;
use App\Models\Order;
use Dnetix\Redirection\PlacetoPay;
use Illuminate\Support\Facades\Auth;

class PlacetoPayGateway implements PaymentGatewayContract
{
    public function __construct(
        private PlacetoPay $gateway
    ) {
    }

    public function pay(Order $order): PaymentResponse
    {
        $response = $this->gateway->request($this->preparePaymentRequest($order));
        $status = $response->status()->status();
        $message = $response->status()->message();

        if ($response->isSuccessful()) {
            $status = AppConstants::CREATED;
        } else {
            LogGeneralEvent::dispatch('error', 'PlacetoPay (pay): ' . $message);
        }

        $statusResponse = new StatusResponse($status, $message);
        return new PaymentResponse($statusResponse, $response->requestId, $response->processUrl);
    }

    public function getStatus(string $requestId): StatusResponse
    {
        $response = $this->gateway->query($requestId);
        return new StatusResponse($response->status()->status(), $response->status()->message());
    }

    private function preparePaymentRequest(Order $order): array
    {
        $reference_id = $order->referencePayment;
        return [
            'buyer' => [
                'name' => Auth::user()->name,
                'surname' => Auth::user()->surname,
                'email' => Auth::user()->email,
                'documentType' => Auth::user()->document_type,
                'document' => Auth::user()->document,
                'mobile' => Auth::user()->phone,
            ],
            'payment' => [
                'reference' => $reference_id,
                'description' => $order->totalProducts . ' Products',
                'amount' => [
                    'currency' => $order->currency,
                    'total' => $order->totalAmount,
                ],
            ],
            'expiration' => date('c', strtotime('+' . config('constants.expiration_days') . ' days')),
            'returnUrl' => route('payment.changeStatus', $reference_id),
            'ipAddress' => request()->ip(),
            'userAgent' => request()->header('user-agent'),
        ];
    }
}
