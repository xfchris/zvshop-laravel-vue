<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function getOrderByUser(): Order
    {
        return Auth::user()->order;
    }

    public function addOrUpdateProduct(Request $request, Product $product): void
    {
        $order = $this->getOrderByUser();
        $productOrder = $order->products()->find($product);
        $quantity = $request->input('quantity');

        if ($productOrder) {
            $this->updateProduct($quantity, $productOrder);
        } else {
            $this->addProduct($quantity, $order, $product);
        }
    }

    private function addProduct(int $quantity, Order $order, Product $product): void
    {
        $order->products()->attach($product, [
            'quantity' => $quantity,
        ]);
    }

    private function updateProduct(int $quantity, Product $product): void
    {
        $product->pivot->quantity = $quantity;
        $product->pivot->save();
    }

    public function updateOrderAddress(Request $request): bool
    {
        $order = $this->getOrderByUser();
        $order->name_receive = $request->input('name_receive');
        $order->address = $request->input('address');
        $order->phone = $request->input('phone');

        return $order->save();
    }

    public function removeProduct(Product $product): bool
    {
        $order = $this->getOrderByUser();
        $order->products()->detach($product);

        if ($order->products()->count() == 0) {
            return $order->delete();
        }
        return false;
    }

    public function deleteOrder(): bool
    {
        $order = $this->getOrderByUser();
        return $order->delete();
    }
}
