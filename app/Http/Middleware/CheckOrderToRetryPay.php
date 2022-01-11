<?php

namespace App\Http\Middleware;

use App\Constants\AppConstants;
use App\Models\Order;
use App\Models\Product;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class CheckOrderToRetryPay
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        $order = $request->order;

        if (!in_array($order->status, [AppConstants::REJECTED, AppConstants::EXPIRED])) {
            return redirect()->back()->with('error', 'This order is in ' . $order->status . ' status');
        }
        $messageError = $this->checkOrderProductsValid($order);
        if ($messageError) {
            return redirect()->back()->with('error', 'We are sorry, ' . $messageError);
        }
        return $next($request);
    }

    private function checkOrderProductsValid(Order $order): ?string
    {
        $productsId = array_map(fn ($p) => $p['pivot']['product_id'], $order->payment->products);

        $storeProducts = Product::select('id', 'price', 'quantity')->find($productsId)->keyBy('id');
        $response = null;

        foreach ($order->payment->products as $product) {
            $storeProduct = Arr::get($storeProducts, $product['pivot']['product_id']);

            if (!$storeProduct) {
                $response = 'product (' . $product['name'] . ') does not exist';
            } elseif ($product['price'] != $storeProduct->price) {
                $response = 'the product price: (' . $product['name'] . ') has changed. New price: ' . $storeProduct->price;
            } elseif ($product['pivot']['quantity'] > $storeProduct->quantity) {
                $response = 'there is not enough quantity of products for the product: ' . $product['name'] . '. Quantity: ' . $storeProduct->quantity;
            }
        }
        return $response;
    }
}
