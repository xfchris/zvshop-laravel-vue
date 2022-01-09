<?php

namespace App\Factories\PaymentGateway;

use App\Constants\AppConstants;
use App\Events\LogGeneralEvent;
use App\Factories\PaymentGateway\Responses\PaymentResponse;
use App\Factories\PaymentGateway\Responses\StatusResponse;
use App\Models\Order;
use Dnetix\Redirection\PlacetoPay;

class PlacetoPayGateway implements PaymentGateway
{
    public function __construct(
        private PlacetoPay $gateway
    ) {
    }

    public function pay(Order $order): PaymentResponse
    {
        $reference_id = $order->referencePayment;
        $request = [
            'payment' => [
                'reference' => $reference_id,
                'description' => $order->totalProducts . ' Products',
                'amount' => [
                    'currency' => config('constants.currency'),
                    'total' => $order->totalAmount,
                ],
            ],
            'expiration' => date('c', strtotime('+' . config('constants.expiration_days') . ' days')),
            'returnUrl' => route('payment.accept', $reference_id),
            'cancelUrl' => route('payment.cancel', $reference_id),
            'ipAddress' => request()->ip(),
            'userAgent' => request()->header('user-agent'),
        ];
        $response = $this->gateway->request($request);
        $status = $response->status()->status();
        $message = $response->status()->message();

        if ($response->isSuccessful()) {
            $status = AppConstants::CREATED;
        } else {
            LogGeneralEvent::dispatch(['level' => 'error', 'message' => 'PlacetoPay (pay): ' . $message]);
        }

        $statusResponse = new StatusResponse($status, $message);
        return new PaymentResponse($statusResponse, $response->requestId, $response->processUrl);
    }

    public function getStatus(string $requestId): StatusResponse
    {
        $response = $this->gateway->query($requestId);

        return new StatusResponse(
            $response->status()->status(),
            $response->status()->message()
        );
    }
}
