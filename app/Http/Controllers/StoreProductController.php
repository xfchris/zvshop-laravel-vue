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

    public function index(Request $request, ?string $category_slug = null): View
    {
        $category = $this->productService->getCategoryBySlug($category_slug);
        $category_id = $category ? $category->id : null;
        $products = $this->productService->getOrSearchProductsPerPage($category_id, $request->get('q'));

        return view('store.products.index', ['products' => $products, 'category' => $category]);
    }

    public function show(Product $product): View
    {
        return view('store.products.show', ['product' => $product]);
    }
}
