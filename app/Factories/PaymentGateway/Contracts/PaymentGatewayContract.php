<?php

namespace App\Factories\PaymentGateway\Contracts;

use App\Factories\PaymentGateway\Responses\PaymentResponse;
use App\Factories\PaymentGateway\Responses\StatusResponse;
use App\Models\Order;

interface PaymentGatewayContract
{
    public function pay(Order $order): PaymentResponse;

    public function getStatus(string $requestId): StatusResponse;
}
