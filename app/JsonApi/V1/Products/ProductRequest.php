<?php

namespace App\JsonApi\V1\Products;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class ProductRequest extends ResourceRequest
{
    public function rules(): array
    {
        return $this->isMethod('patch') ? ProductUpdateRequest::rulesRequest()
                                        : ProductStoreRequest::rulesRequest();
    }
}
