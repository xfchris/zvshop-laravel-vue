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
        $message = 'Image could not be deleted';

        if ($this->productService->removeImage($image)) {
            $status = 'success';
            $message = 'Image deleted';
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
}
