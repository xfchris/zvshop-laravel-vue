<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductOrderRequest;
use App\Models\Product;
use App\Services\Order\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        public OrderService $orderService
    ) {
    }

    public function show(): View
    {
        $order = $this->orderService->getOrderByUser();
        return view('store.orders.show', ['order' => $order]);
    }

    public function deleteOrder(): RedirectResponse
    {
        $this->orderService->deleteOrder();
        return redirect()->route('store.order.show');
    }

    public function addProduct(AddProductOrderRequest $request, Product $product): RedirectResponse
    {
        $this->orderService->addOrUpdateProduct($request, $product);
        return redirect()->route('store.order.show');
    }

    public function removeProduct(Product $product): RedirectResponse
    {
        $this->orderService->removeProduct($product);
        return redirect()->route('store.order.show');
    }
}
