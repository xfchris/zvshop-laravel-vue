<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductService
{
    public function createProduct(Request $request, Product $product): Product
    {
        $product->create($request->validated());
        return $product;
    }

    public function updateProduct(Request $request, Product $product): Product
    {
        $product->update($request->validated());
        return $product;
    }

    public function disableProduct(int $id): ?bool
    {
        $product = Product::withTrashed()->find($id);
        return $product->delete();
    }

    public function enableProduct(int $id): ?bool
    {
        $product = Product::withTrashed()->find($id);
        return $product->restore();
    }
}
