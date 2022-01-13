<?php

namespace Tests\Feature\Http\Controllers;

use App\Constants\AppConstants;
use Dnetix\Redirection\Entities\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\Traits\PaymentGatewayFake;
use Tests\Traits\PaymentManagement;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;
    use PaymentGatewayFake;
    use PaymentManagement;

    public function test_it_can_initiate_a_payment_to_pending_status(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);

        $response = $this->initPayOrder($user);

        $response->assertRedirect(config('services.placetopay.url') . self::$processUrl);
        $order = $user->orders()->with('products:id,quantity')->first();
        $this->assertSame($order->status, AppConstants::PENDING);
        $this->assertSame($order->products[0]->quantity, 2);
    }

    public function test_it_cannot_start_a_payment_when_there_is_already_a_payment_started(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $user->fresh();
        $response = $this->initPayOrder($user);
        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    public function test_it_show_error_when_the_service_found_any_error(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);

        $response = $this->initPayOrder($user, Status::ST_FAILED);

        $response->assertSessionHas('error');
        $response->assertRedirect();
    }

    public function test_it_show_error_when_retry_a_payment_and_the_product_has_been_changed_in_price(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $order = $user->orders()->first();
        $order->products()->update(['price' => 9999.0]);

        $this->setStatusPayment($user->orders()->latest()->first()->payment, AppConstants::REJECTED);

        $response = $this->actingAs($user)->post(route('payment.retryPay', $order));

        $response->assertSessionHas('error');
        $response->assertRedirect();
    }

    public function test_it_show_error_when_retry_a_payment_and_the_product_has_been_changed_in_quantity(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $this->setStatusPayment($user->orders()->latest()->first()->payment, AppConstants::REJECTED);
        $order = $user->orders()->first();
        $order->products()->update(['products.quantity' => 0]);

        $response = $this->actingAs($user)->post(route('payment.retryPay', $order));

        $response->assertSessionHas('error');
        $response->assertRedirect();
    }

    public function test_it_show_error_when_retry_a_payment_and_the_product_not_exist(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $this->setStatusPayment($user->orders()->latest()->first()->payment, AppConstants::REJECTED);
        $order = $user->orders()->first();
        $order->products()->delete();

        $response = $this->actingAs($user)->post(route('payment.retryPay', $order));

        $response->assertSessionHas('error');
        $response->assertRedirect();
    }

    public function test_it_show_error_when_retry_a_payment_of_approved_order(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $this->setStatusPayment($user->orders()->latest()->first()->payment, AppConstants::APPROVED);

        $response = $this->actingAs($user)->post(route('payment.retryPay', $user->orders()->first()));

        $response->assertSessionHas('error');
        $response->assertRedirect();
    }

    public function test_it_can_retry_a_payment(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $this->setStatusPayment($user->orders()->latest()->first()->payment, AppConstants::REJECTED);

        $response = $this->actingAs($user)->post(route('payment.retryPay', $user->orders()->first()));

        $response->assertRedirect(config('services.placetopay.url') . self::$processUrl);
        $order = $user->orders()->with('products:id,quantity')->first();
        $this->assertSame($order->status, AppConstants::PENDING);
        $this->assertSame($order->products[0]->quantity, 2);
    }

    public function test_it_show_error_when_the_service_found_any_error_retrying_to_pay(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $this->setStatusPayment($user->orders()->latest()->first()->payment, AppConstants::REJECTED);
        $this->fakeInstancePlacetoPay(Status::ST_FAILED, null);

        $response = $this->actingAs($user)->post(route('payment.retryPay', $user->orders()->first()));

        $response->assertSessionHas('error');
        $response->assertRedirect();
    }

    public function test_it_can_change_payment_status_pending_to_approved(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);

        $this->fakeInstancePlacetoPay(null, AppConstants::APPROVED);
        $order = $user->orders()->latest()->first();

        $response = $this->actingAs($user)->get(route('payment.changeStatus', $order->payment->reference_id));
        $response->assertSessionHas('info', trans('app.payment.info_status_changed', ['status' => strtolower(AppConstants::APPROVED)]));
    }

    public function test_it_cannot_change_payment_status_expired_to_approved(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);

        $this->fakeInstancePlacetoPay(null, AppConstants::APPROVED);
        $order = $user->orders()->latest()->first();
        $this->setStatusPayment($order->payment, AppConstants::EXPIRED);

        $response = $this->actingAs($user)->get(route('payment.changeStatus', $order->payment->reference_id));
        $response->assertSessionHas('info', trans('app.payment.info_status_changed', ['status' => strtolower(AppConstants::EXPIRED)]));
    }

    public function test_it_show_the_order_list(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);

        $response = $this->actingAs($user)->get(route('payment.orders'));
        $response->assertSee(trans('app.payment.order_list'));
    }

    public function test_it_show_the_order_details(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $this->initPayOrder($user);
        $order = $user->orders()->first();

        $response = $this->actingAs($user)->get(route('payment.details', $order->id));

        $response->assertSee(trans('app.payment.order_list'));
    }

    public function test_it_show_error_when_a_user_tries_to_view_payments_from_another_user(): void
    {
        $otherUser = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $user = $this->userClientCreate();

        $response = $this->actingAs($user)->get(route('payment.details', $otherUser->orders()->first()->id));
        $response->assertStatus(403);
    }

    public function test_it_show_error_when_a_user_not_have_permission_to_view_payments(): void
    {
        $user = $this->generateOrderWithProducts(2, ['quantity' => 3], 1);
        $role = Role::findByName(config('permission.roles.clients.name'));
        $role->revokePermissionTo(config('permission.names.user_manage_own_order'));

        $response = $this->actingAs($user)->get(route('payment.details', $user->orders()->first()->id));
        $response->assertStatus(403);
    }
}
