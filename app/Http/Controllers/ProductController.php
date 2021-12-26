<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ProductController extends Controller
{
    public function __construct(
        public ProductService $productService
    ) {
    }

    public function index(): View
    {
        $products = Product::with('category:id,name')->withTrashed()->paginate(config('constants.num_rows_per_table'));
        $categories = Category::get();
        return view('products.index', ['products' => $products, 'categories' => $categories]);
    }

    public function create(): View
    {
        $categories = Category::get();
        return view('products.create', ['categories' => $categories]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $this->productService->createProduct($request, $product);
        return back()->with('success', 'Product create!');
    }

    //puede verlo cualquiera
    public function show(Product $product): View
    {
        return view('products.show', ['product' => $product]);
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->productService->updateProduct($request, $product);
        return redirect()->route('admin.products.index')->with('success', 'Product update!');
    }

    public function disable(int $id): RedirectResponse
    {
        $this->productService->disableProduct($id);
        return back()->with('success', Lang::get('app.product_management.alert_disabled'));
    }

    public function enable(int $id): RedirectResponse
    {
        $this->productService->enableProduct($id);
        return back()->with('success', Lang::get('app.product_management.alert_enabled'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return back();
    }
}

/**
 * Falta:
 * FormRequest
 * Vistas
 * Ejecutar migraciones
 * Testing.
 *
 * https://github.com/juancolo/MercaTodoSV/blob/develop/app/Http/Requests/ProductRequest.php
 */
