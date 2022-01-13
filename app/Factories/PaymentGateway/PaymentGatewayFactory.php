<?php

namespace App\Factories\PaymentGateway;

use App\Factories\PaymentGateway\Contracts\PaymentGatewayContract;
use Dnetix\Redirection\PlacetoPay;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    public function make(string $gateway): PaymentGatewayContract
    {
        return match (strtolower($gateway)) {
            'placetopay' => $this->createPlacetoPay(),
            default => throw new InvalidArgumentException('Payment gateway ' . $gateway . ' is not supported'),
        };
    }

    protected function createPlacetoPay(): PlacetoPayGateway
    {
        return new PlacetoPayGateway(new PlacetoPay(config('services.placetopay')));
    }
}
