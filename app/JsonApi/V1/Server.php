<?php

namespace App\JsonApi\V1;

use App\JsonApi\V1\Categories\CategorySchema;
use App\JsonApi\V1\Products\ProductSchema;
use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{
    protected string $baseUri = '/api/v1';

    protected function allSchemas(): array
    {
        return [
            ProductSchema::class,
            CategorySchema::class,
        ];
    }
}
