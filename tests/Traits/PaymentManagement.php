<?php

namespace Tests\Traits;

use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Dnetix\Redirection\Entities\Status;
use Illuminate\Testing\TestResponse;

trait PaymentManagement
{
    public function setStatusPayment(Payment $payment, string $status): void
    {
        $this->fakeInstancePlacetoPay(Status::ST_OK, null);
        $payment->update(['status' => $status]);
    }

    public function initPayOrder(User $user, string $statusPaymentGateway = Status::ST_OK): TestResponse
    {
        $this->fakeInstancePlacetoPay($statusPaymentGateway, null);
        return $this->actingAs($user)->post(route('payment.pay'), [
            'name_receive' => $user->name,
            'address' => $user->address,
            'phone' => $user->phone,
        ]);
    }

    public function generateOrderWithProducts(int $numProducts, ?array $attributesProducts, $quantityPerProductsToBuy): User
    {
        $user = $this->userClientCreate();
        $products = Product::factory($numProducts)->create($attributesProducts);
        foreach ($products as $product) {
            $this->actingAs($user)->post(route('store.order.addProduct', $product), [
                'quantity' => $quantityPerProductsToBuy,
            ]);
        }
        return $user;
    }
}
