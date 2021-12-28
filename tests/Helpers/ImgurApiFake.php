<?php

namespace Tests\Helpers;

use Imgur\Api\AbstractApi;
use Imgur\Client;
use Imgur\Pager\PagerInterface;

class ImgurApiFake extends AbstractApi
{
    public function __construct(Client $client, ?PagerInterface $pager = null)
    {
        parent::__construct($client, $pager);
    }

    public function upload()
    {
        return ['id' => 1, 'link' => 'https://i.imgur.com/fakehash.jpg', 'deletehash' => 'fakeDeleteHash'];
    }

    public function deleteImage()
    {
        return true;
    }
}
