<?php

namespace Tests\Traits;

use App\Strategies\GstImages\ContextImage;
use App\Strategies\GstImages\GstImgur;
use Tests\Helpers\ImgurClientFake;
use Throwable;

trait ContextImageFake
{
    protected function fakeInstanceImage(
        ?Throwable $errorUpload = null,
        ?Throwable $errorDeleteImage = null
    ): void {
        $this->app->instance(
            ContextImage::class,
            new ContextImage(
                new GstImgur(
                    ['client_id' => null, 'client_secret' => null],
                    new ImgurClientFake($errorUpload, $errorDeleteImage)
                )
            )
        );
    }
}
