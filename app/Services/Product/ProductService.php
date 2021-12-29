<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Services\Trait\ImageTrait;
use App\Services\Trait\NotifyLog;
use App\Strategies\GstImages\ContextImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use MeiliSearch\Endpoints\Indexes;

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
        $product->fill($request->all())->save();
        $this->notifyLog('product', 'Procuct', $product->id, 'created');
        return $product;
    }

    public function updateProduct(Request $request, int $id): Product
    {
        $product = Product::withTrashed()->find($id);
        $product->update($request->all());
        $this->notifyLog('product', 'Procuct', $product->id, 'updated');
        return $product;
    }

    public function createImages($column, Request $request, Model $model): ?string
    {
        if ($request->hasFile($column)) {
            $urlimages = $this->uploadImagesFile($column, $request, $model);
            if (count($urlimages)) {
                $model->images()->createMany($urlimages);
            } else {
                return ' bat, ' . strtolower(trans('app.image_management.error_uplading_image'));
            }
        }
        return null;
    }

    public function disableProduct(int $id): ?bool
    {
        $product = Product::find($id);
        $this->notifyLog('product', 'Procuct', $product->id, 'disabled');

        return $product->delete();
    }

    public function enableProduct(int $id): ?bool
    {
        $product = Product::onlyTrashed()->find($id);
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

    public function SearchProductsPerPage(int $category_id = null, string $search = null): LengthAwarePaginator
    {
        $products = Product::search($search, function (Indexes $index, $query, $options) use ($category_id) {
            if ($category_id) {
                $options['filters'] = '(category_id = ' . $category_id . ')';
            }
            return $index->rawSearch($query, $options);
        });

        return $products->paginate(config('constants.num_product_rows_per_table'));
    }

    public function getProductsStorePerPage(int $category_id = null): LengthAwarePaginator
    {
        $products = Product::with('category:id,name', 'images');
        if ($category_id) {
            $products->where('category_id', $category_id);
        }

        return $products->orderBy('created_at', 'DESC')->paginate(config('constants.num_product_rows_per_table'));
    }
}
