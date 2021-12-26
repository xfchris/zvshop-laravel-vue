<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function __construct(
        public ProductService $productService
    ) {
    }

    public function index(): View
    {
        return view(
            'products.index',
            [
                'products' => $this->productService->getProductsPerPage(),
                'categories' => Category::get(),
            ]
        );
    }

    public function create(Product $product): View
    {
        $categories = Category::get();
        return view('products.create', ['categories' => $categories, 'product' => $product]);
    }

    public function store(ProductStoreRequest $request, Product $product): RedirectResponse
    {
        $this->productService->createProduct($request, $product);
        return redirect()->route('admin.products.index')->with('success', trans('app.product_management.product_create'));
    }

    //puede verlo cualquiera
    public function show(Product $product): View
    {
        $categories = Category::select('id,name')->get();
        return view('products.show', ['product' => $product, 'categories' => $categories]);
    }

    public function edit(int $id): View
    {
        $product = Product::with('images', 'category:id,name')->withTrashed()->find($id);
        $categories = Category::select('id', 'name')->get();
        return view('products.edit', ['product' => $product, 'categories' => $categories]);
    }

    public function update(ProductUpdateRequest $request, int $id): RedirectResponse
    {
        $this->productService->updateProduct($request, $id);
        return redirect()->route('admin.products.index')->with('success', trans('app.product_management.product_update'));
    }

    public function disable(int $id): RedirectResponse
    {
        $this->productService->disableProduct($id);
        return back()->with('success', trans('app.product_management.alert_disabled'));
    }

    public function enable(int $id): RedirectResponse
    {
        $this->productService->enableProduct($id);
        return back()->with('success', trans('app.product_management.alert_enabled'));
    }
}
