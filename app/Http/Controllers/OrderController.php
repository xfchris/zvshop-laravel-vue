<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProductOrderRequest;
use App\Models\Product;
use App\Services\Order\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public static string $defaultView = 'store.order.show';

    public function __construct(
        public OrderService $orderService
    ) {
    }

    public function show(): View
    {
        $order = $this->orderService->getOrderByUser();
        $this->authorize('update', $order);
        return view('store.orders.show', ['order' => $order]);
    }

    public function deleteOrder(): RedirectResponse
    {
        $this->authorize('can', 'store_update_order');
        $this->orderService->deleteOrder();
        return redirect()->route(self::$defaultView);
    }

    public function addProduct(AddProductOrderRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('can', 'store_update_order');
        $this->orderService->addOrUpdateProduct($request, $product);
        return redirect()->route(self::$defaultView);
    }

    public function removeProduct(Product $product): RedirectResponse
    {
        $this->authorize('can', 'store_update_order');
        $this->orderService->removeProduct($product);
        return redirect()->route(self::$defaultView);
    }
}
