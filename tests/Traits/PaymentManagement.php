<?php

namespace Tests\Traits;

use App\Constants\AppConstants;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Dnetix\Redirection\Entities\Status;
use Illuminate\Database\Eloquent\Collection;
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

    public function generateOrderWithProducts(int $numProducts, ?array $attributesProducts, $quantityPerProductsToBuy): user
    {
        $products = Product::factory($numProducts)->create($attributesProducts);
        return $this->generateOrderWithPassProducts($products, $quantityPerProductsToBuy);
    }

    public function generateOrderWithPassProducts(Collection $products, $quantityPerProductsToBuy): User
    {
        $user = $this->userClientCreate();
        foreach ($products as $product) {
            $this->actingAs($user)->post(route('store.order.addProduct', $product), [
                'quantity' => $quantityPerProductsToBuy,
            ]);
        }
        return $user;
    }

    public function generateOrdersApproved(Collection $products, int $numBuyers): void
    {
        for ($i = 1; $i <= $numBuyers; $i++) {
            $user = $this->generateOrderWithPassProducts($products, 2);

            $this->initPayOrder($user);
            $this->setStatusPayment($user->orders()->latest()->first()->payment, AppConstants::APPROVED);
        }
    }
}
