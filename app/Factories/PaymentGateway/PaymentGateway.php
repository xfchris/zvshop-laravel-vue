<?php

namespace App\Factories\PaymentGateway;

use App\Factories\PaymentGateway\Responses\PaymentResponse;
use App\Factories\PaymentGateway\Responses\StatusResponse;
use App\Models\Order;

interface PaymentGateway
{
    public function pay(Order $order): PaymentResponse;

    public function getStatus(string $requestId): StatusResponse;
}
