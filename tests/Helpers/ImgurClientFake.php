<?php

namespace Tests\Helpers;

use Imgur\Api\AbstractApi;
use Imgur\Client;
use Imgur\Pager\PagerInterface;
use Throwable;

class ImgurClientFake extends Client
{
    public function __construct(
        public ?Throwable $errorUpload = null,
        public ?Throwable $errorDeleteImage = null
    ) {
    }

    public function setOption(string $name, $value): void
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function api(string $name, PagerInterface $pager = null): AbstractApi
    {
        return new ImgurApiFake($this);
    }
}
