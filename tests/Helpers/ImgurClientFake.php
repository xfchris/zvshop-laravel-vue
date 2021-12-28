<?php

namespace Tests\Helpers;

use Imgur\Api\AbstractApi;
use Imgur\Client;
use Imgur\Pager\PagerInterface;

class ImgurClientFake extends Client
{
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
