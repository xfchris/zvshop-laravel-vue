<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StoreProductController extends Controller
{
    public function __construct(
        public ProductService $productService,
    ) {
    }

    public function index(Request $request, ?string $categorySlug = null): View
    {
        $this->authorize('can', 'store_show_products');

        $category = $this->productService->getCategoryBySlug($categorySlug);
        $category_id = $category ? $category->id : null;
        $products = $this->productService->getOrSearchProductsPerPage($category_id, $request->get('q'));

        return view('store.products.index', ['products' => $products, 'category' => $category]);
    }

    public function show(Product $product): View
    {
        $this->authorize('can', 'store_show_products');
        return view('store.products.show', ['product' => $product]);
    }
}
