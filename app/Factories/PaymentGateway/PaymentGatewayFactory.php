<?php

namespace App\Factories\PaymentGateway;

use Dnetix\Redirection\PlacetoPay;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    public function make(string $gateway): PaymentGateway
    {
        return match (strtolower($gateway)) {
            'placetopay' => $this->createPlacetoPay(),
            default => throw new InvalidArgumentException('Payment gateway ' . $gateway . ' is not supported'),
        };
    }

    protected function createPlacetoPay(): PlacetoPayGateway
    {
        $gateway = new PlacetoPay(config('services.placetopay'));
        return new PlacetoPayGateway($gateway);
    }
}
