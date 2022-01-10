<?php

namespace App\Factories\PaymentGateway\Responses;

class StatusResponse
{
    public function __construct(
        public string $status,
        public string $message
    ) {
    }
}
