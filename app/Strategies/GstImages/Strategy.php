<?php

namespace App\Strategies\GstImages;

interface Strategy
{
    public function upload(array $data, string $typeFile): ResponseImage;
    public function getSize(string $id, ?string $size): string;
    public function remove(ResponseImage $responseImage): bool;
}
