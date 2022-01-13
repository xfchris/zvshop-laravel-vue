<?php

namespace App\Providers;

use App\Factories\PaymentGateway\Contracts\PaymentGatewayContract;
use App\Factories\PaymentGateway\PaymentGatewayFactory;
use Illuminate\Support\ServiceProvider;

class PaymentGatewayProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayContract::class, function () {
            return (new PaymentGatewayFactory())->make('placetopay');
        });
    }
}
