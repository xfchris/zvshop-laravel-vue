<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Strategies\GstImages\ContextImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminProductController extends Controller
{
    public function __construct(
        public ProductService $productService
    ) {
    }

    public function index(): View
    {
        $this->authorize('can', 'users_show_products');
        return view(
            'products.index',
            ['products' => $this->productService->getProductsPerPage(), 'categories' => Category::get()]
        );
    }

    public function create(Product $product): View
    {
        $this->authorize('can', 'users_create_products');
        $categories = Category::get();
        return view('products.create', ['categories' => $categories, 'product' => $product]);
    }

    public function store(ProductStoreRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('can', 'users_create_products');
        $product = $this->productService->createProduct($request, $product);
        $msgImages = $this->productService->createImages('images', $request, $product);

        return redirect()->route('admin.products.index')->with('success', trans('app.product_management.product_create') . $msgImages);
    }

    public function edit(int $id, ContextImage $contextImage): View
    {
        $this->authorize('can', 'users_update_products');
        $product = Product::with('images', 'category:id,name')->withTrashed()->find($id);
        $categories = Category::select('id', 'name')->get();

        return view('products.edit', ['product' => $product, 'categories' => $categories, 'contextImage' => $contextImage]);
    }

    public function update(ProductUpdateRequest $request, int $id): RedirectResponse
    {
        $this->authorize('can', 'users_update_products');
        $product = $this->productService->updateProduct($request, $id);
        $msgImages = $this->productService->createImages('images', $request, $product);

        return redirect()->route('admin.products.edit', $id)
                         ->with('success', trans('app.product_management.product_update') . $msgImages);
    }

    public function disable(int $id): RedirectResponse
    {
        $this->authorize('can', 'users_disable_products');
        $this->productService->disableProduct($id);
        return back()->with('success', trans('app.product_management.alert_disabled'));
    }

    public function enable(int $id): RedirectResponse
    {
        $this->authorize('can', 'users_enable_products');
        $this->productService->enableProduct($id);
        return back()->with('success', trans('app.product_management.alert_enabled'));
    }

    public function exportDownload(string $dir, string $filename): StreamedResponse
    {
        abort_if(!Storage::exists($dir . '/' . $filename), 404);
        return Storage::download($dir . '/' . $filename);
    }
}
