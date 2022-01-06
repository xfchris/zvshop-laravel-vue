<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_it_show_the_user_order_empty(): void
    {
        $user = $this->userClientCreate();

        $response = $this->actingAs($user)->get(route('store.order.show'));

        $response->assertStatus(200);
        $response->assertSee(trans('app.cart_empty'));
    }

    public function test_it_can_add_a_product_to_order(): void
    {
        $user = $this->userClientCreate();
        $products = Product::factory(2)->create();

        $this->actingAs($user)->post(route('store.order.addProduct', $products[0]->id), [
            'quantity' => $products[0]->quantity,
        ]);
        $response = $this->actingAs($user)->get(route('store.order.show'));

        $response->assertStatus(200);
        $response->assertDontSee(trans('app.cart_empty'));
        $response->assertSee($products[0]->name);
        $response->assertDontSee($products[1]->name);
    }

    public function test_it_can_update_quantity_of_product_to_order(): void
    {
        $user = $this->userClientCreate();
        $product = Product::factory()->create(['quantity' => 3]);

        $this->actingAs($user)->post(route('store.order.addProduct', $product->id), [
            'quantity' => 2,
        ]);
        $this->actingAs($user)->post(route('store.order.addProduct', $product->id), [
            'quantity' => 1,
        ]);

        $this->assertSame((int)$user->order->products[0]->pivot->quantity, 1);
    }

    public function test_it_cannot_add_a_product_to_order_when_exceeds_the_available_quantity(): void
    {
        $user = $this->userClientCreate();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post(route('store.order.addProduct', $product->id), [
            'quantity' => $product->quantity + 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('quantity');
    }

    public function test_it_can_clear_order_list(): void
    {
        $user = $this->userClientCreate();
        $products = Product::factory(2)->create();
        foreach ($products as $product) {
            $this->actingAs($user)->post(route('store.order.addProduct', $product->id), [
                'quantity' => $product->quantity,
            ]);
        }

        $response = $this->followingRedirects()->actingAs($user)->delete(route('store.order.deleteOrder'));

        $response->assertStatus(200);
        $response->assertSee(trans('app.cart_empty'));
    }

    public function test_it_can_remove_a_product_from_the_order(): void
    {
        $user = $this->userClientCreate();
        $products = Product::factory(2)->create();
        foreach ($products as $product) {
            $this->actingAs($user)->post(route('store.order.addProduct', $product->id), [
                'quantity' => $product->quantity,
            ]);
        }

        $response = $this->followingRedirects()->actingAs($user)->delete(route('store.order.deleteProduct', $products[1]->id));

        $response->assertStatus(200);
        $response->assertDontSee(trans('app.cart_empty'));
        $response->assertSee($products[0]->name);
        $response->assertDontSee($products[1]->name);
    }

    public function test_it_can_remove_a_order_when_the_only_product_is_removed(): void
    {
        $user = $this->userClientCreate();
        $product = Product::factory()->create();
        $this->actingAs($user)->post(route('store.order.addProduct', $product->id), [
            'quantity' => $product->quantity,
        ]);

        $this->actingAs($user)->delete(route('store.order.deleteProduct', $product->id));

        $this->assertSame(0, Order::count());
    }
}
