<?php

namespace App\Factories\PaymentGateway\Responses;

class PaymentResponse
{
    public function __construct(
        public StatusResponse $statusResponse,
        public ?string $requestId,
        public ?string $processUrl,
    ) {
    }
}
