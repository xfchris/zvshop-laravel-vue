<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;

class ApiProductController extends Controller
{
    public function __construct(
        public ProductService $productService
    ) {
    }

    public function removeImage(Image $image): JsonResponse
    {
        $status = 'error';
        $message = trans('app.image_management.image_no_removed');

        if ($this->productService->removeImage($image)) {
            $status = 'success';
            $message = trans('app.image_management.image_removed');
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
