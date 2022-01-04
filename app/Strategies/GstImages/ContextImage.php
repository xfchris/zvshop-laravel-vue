<?php

namespace App\Strategies\GstImages;

class ContextImage
{
    public function __construct(
        private Strategy $strategy
    ) {
    }

    /**
     * @param array $data  ['image','type'] ej: type:url, type:file, type:base64
     * @param string $type
     *
     * @return ResponseImage
     */
    public function upload(array $data, string $typeFile = 'image'): ResponseImage
    {
        return $this->strategy->upload($data, $typeFile);
    }

    public function getSize(string $id, ?string $size = null): string
    {
        return $this->strategy->getSize($id, $size);
    }

    public function remove(ResponseImage $responseImage): bool
    {
        return $this->strategy->remove($responseImage);
    }
}
