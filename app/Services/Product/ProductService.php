<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductService
{
    public function createProduct(Request $request, Product $product): Product
    {
        $product->create($request->all());
        return $product;
    }

    public function updateProduct(Request $request, int $id): Product
    {
        $product = Product::withTrashed()->find($id);
        $product->update($request->all());
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

    public function getProductsPerPage(): LengthAwarePaginator
    {
        return Product::with('category:id,name')
                    ->withTrashed()
                    ->orderBy('created_at', 'DESC')
                    ->paginate(config('constants.num_rows_per_table'));
    }
}
