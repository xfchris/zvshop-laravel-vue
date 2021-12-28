<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\Trait\ImageTrait;
use App\Services\Trait\NotifyLog;
use App\Strategies\GstImages\ContextImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductService
{
    use ImageTrait;
    use NotifyLog;

    public function __construct(
        protected ContextImage $contextImage
    ) {
    }

    public function createProduct(Request $request, Product $product): Product
    {
        if ($product->fill($request->all())->save()) {
            $this->uploadImagesFile('images', $request, $product);
        }
        $this->notifyLog('product', 'Procuct', $product->id, 'created');

        return $product;
    }

    public function updateProduct(Request $request, int $id): Product
    {
        $product = Product::withTrashed()->find($id);
        if ($product->update($request->all())) {
            $this->uploadImagesFile('images', $request, $product);
        }
        $this->notifyLog('product', 'Procuct', $product->id, 'updated');
        return $product;
    }

    public function disableProduct(int $id): ?bool
    {
        $product = Product::withTrashed()->find($id);
        $this->notifyLog('product', 'Procuct', $product->id, 'disabled');

        return $product->delete();
    }

    public function enableProduct(int $id): ?bool
    {
        $product = Product::withTrashed()->find($id);
        $this->notifyLog('product', 'Procuct', $product->id, 'enabled');

        return $product->restore();
    }

    public function getProductsPerPage(): LengthAwarePaginator
    {
        return Product::with('category:id,name', 'images')
                    ->withTrashed()
                    ->orderBy('created_at', 'DESC')
                    ->paginate(config('constants.num_rows_per_table'));
    }
}
