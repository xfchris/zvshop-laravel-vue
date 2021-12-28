<?php

namespace Tests\Traits;

use App\Strategies\GstImages\ContextImage;
use App\Strategies\GstImages\GstImgur;
use Tests\Helpers\ImgurClientFake;

trait ContextImageFake
{
    protected function fakeInstanceImage(): void
    {
        $this->app->instance(
            ContextImage::class,
            new ContextImage(
                new GstImgur(
                    ['client_id' => null, 'client_secret' => null],
                    new ImgurClientFake()
                )
            )
        );
    }
}
