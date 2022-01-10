<?php

namespace App\Factories\PaymentGateway\Responses;

use App\Constants\AppConstants;
use InvalidArgumentException;

class StatusResponse
{
    public function __construct(
        public string $status,
        public string $message
    ) {
        $this->validateStatus();
    }

    private function validateStatus(): void
    {
        if (!in_array($this->status, AppConstants::STATUS)) {
            throw new InvalidArgumentException('The status should be one of these:' . json_encode(AppConstants::STATUS));
        }
    }
}
