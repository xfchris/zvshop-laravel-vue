<?php

namespace App\Providers;

use App\Factories\PaymentGateway\PaymentGateway;
use App\Factories\PaymentGateway\PaymentGatewayFactory;
use Illuminate\Support\ServiceProvider;

class PaymentGatewayProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGateway::class, function () {
            return (new PaymentGatewayFactory())->make('placetopay');
        });
    }
}
