<?php

namespace App\Services\Product;

use App\Events\LogUserActionEvent;
use App\Exports\ProductExport;
use App\Helpers\ReportHelper;
use App\Imports\ProductImport;
use App\Jobs\NotifyOfCompletedExport;
use App\Jobs\NotifyOfCompletedImport;
use App\Models\Category;
use App\Models\Product;
use App\Services\Trait\ImageTrait;
use App\Strategies\GstImages\ContextImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Builder as ScoutBuilder;
use MeiliSearch\Endpoints\Indexes;

class ProductService
{
    use ImageTrait;

    public function __construct(
        protected ContextImage $contextImage
    ) {
    }

    public function createProduct(Request $request, Product $product): Product
    {
        $product->fill($request->all())->save();
        LogUserActionEvent::dispatch('product', 'Procuct', $product->id, 'created');

        return $product;
    }

    public function updateProduct(Request $request, int $id): Product
    {
        $product = Product::withTrashed()->find($id);
        $product->update($request->all());
        LogUserActionEvent::dispatch('product', 'Procuct', $product->id, 'updated');

        return $product;
    }

    public function createImages($column, Request $request, Model $model): ?string
    {
        if ($request->hasFile($column)) {
            $urlimages = $this->uploadImagesFile($column, $request, $model);
            if (count($urlimages)) {
                $model->images()->createMany($urlimages);
            } else {
                return trans('app._bat_') . strtolower(trans('app.image_management.error_uplading_image'));
            }
        }
        return null;
    }

    public function disableProduct(int $id): ?bool
    {
        $product = Product::find($id);
        LogUserActionEvent::dispatch('product', 'Procuct', $product->id, 'disabled');

        return $product->delete();
    }

    public function enableProduct(int $id): ?bool
    {
        $product = Product::onlyTrashed()->find($id);
        LogUserActionEvent::dispatch('product', 'Procuct', $product->id, 'enabled');

        return $product->restore();
    }

    public function getProductsPerPage(): LengthAwarePaginator
    {
        return Product::with('category:id,name', 'images')
                    ->withTrashed()
                    ->orderBy('created_at', 'DESC')
                    ->paginate(config('constants.num_rows_per_table'));
    }

    public function getCategoryBySlug(?string $categorySlug): ?Model
    {
        if ($categorySlug) {
            return Category::where('slug', $categorySlug)->first();
        }
        return null;
    }

    public function getOrSearchProductsPerPage(int $category_id = null, string $search = null): LengthAwarePaginator
    {
        $response = $search ? $this->searchProductsPerPage($category_id, $search)
                            : $this->getProductsStorePerPage($category_id);
        return $response->paginate(config('constants.num_product_rows_per_table'));
    }

    public function searchProductsPerPage(int $category_id = null, string $search = null): ScoutBuilder
    {
        $filters = [
            'filters' => $category_id ? '(category_id = ' . $category_id . ')' : '',
        ];
        return Product::search($search, fn (Indexes $index, $query, $options) => ($index->rawSearch($query, array_merge($options, $filters))));
    }

    public function getProductsStorePerPage(int $category_id = null): Builder
    {
        $products = Product::with('category:id,name', 'images');
        if ($category_id) {
            $products->where('category_id', $category_id);
        }
        return $products->orderBy('created_at', 'DESC');
    }

    public function export(): void
    {
        $name = 'products_' . ReportHelper::createReportName() . Auth::user()->id . '.xlsx';
        $dir = config('constants.report_directory');

        (new ProductExport())->queue($dir . $name)->chain([
            new NotifyOfCompletedExport(Auth::user(), 'products', route('products.exportDownload', [trim($dir, '/'), $name])),
        ]);
    }

    public function import(Request $request): void
    {
        $file = $request->file('file');
        $name = $file->getClientOriginalName();

        (new ProductImport(Auth::user(), $name))->queue($file)->chain([new NotifyOfCompletedImport(Auth::user(), $name)]);
    }
}
