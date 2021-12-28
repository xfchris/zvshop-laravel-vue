<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\Product\ProductService;
use App\Strategies\GstImages\ContextImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View as FacadesView;

class StoreProductController extends Controller
{
    public function __construct(
        public ProductService $productService,
        ContextImage $contextImage,
    ) {
        $this->categories = Category::get()->keyBy('slug');
        FacadesView::share('categories', $this->categories);
        FacadesView::share('contextImage', $contextImage);
    }

    public function index(Request $request, string $category_slug = null): View
    {
        $category = null;
        $category_id = null;
        $q = $request->get('q');

        if (isset($this->categories[$category_slug])) {
            $category = ($this->categories[$category_slug]);
            $category_id = $category->id;
        }
        return view('store.products.index', [
            'products' => $this->productService->SearchProductsPerPage($category_id, $q),
            'category' => $category,
            'q' => $q,
        ]);
    }

    public function show(Product $product): View
    {
        return view('products.show', ['product' => $product]);
    }
}
