<?php

namespace Tests\Feature\Console;

use App\Console\Commands\UpdateExpiredOrders;
use App\Constants\AppConstants;
use App\Jobs\UpdateStatusPayments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\PaymentGatewayFake;
use Tests\Traits\PaymentManagement;

class KernelTest extends TestCase
{
    use RefreshDatabase;
    use PaymentGatewayFake;
    use PaymentManagement;

    public function test_it_can_update_expired_orders(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $order = $user->orders()->first();
        $this->setStatusPayment($order->payment, AppConstants::PENDING);

        $this->travel(config('constants.expiration_days') + 2)->days();
        $comando = new UpdateExpiredOrders();
        $comando->handle();

        $this->assertSame(AppConstants::EXPIRED, $order->fresh()->status);
    }

    public function test_it_can_update_status_payments(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $order = $user->orders()->first();
        $this->setStatusPayment($order->payment, AppConstants::PENDING);

        $comando = new UpdateStatusPayments();
        $ptpMock = $this->getPTPPaymentFactoryMock(null, AppConstants::APPROVED)->make('placetopay');
        $comando->handle($ptpMock);

        $this->assertSame(AppConstants::APPROVED, $order->fresh()->status);
    }
}
