<?php

namespace App\Strategies\GstImages;

class ResponseImage
{
    public function __construct(
        public string $id,
        public string $link,
        public ?array $data = null
    ) {
    }
}
