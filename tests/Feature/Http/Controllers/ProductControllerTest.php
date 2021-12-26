<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_show_the_user_products(): void
    {
        $user = $this->userAdminCreate();
        $response = $this->actingAs($user)->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertSee(trans('app.product_management.title'));
    }

    public function test_it_can_disable_a_product(): void
    {
        $user = $this->userAdminCreate();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.products.disable', $product->id));

        $this->assertNull(Product::find($product->id));
        $response->assertRedirect();
        $response->assertSessionHas('success', trans('app.product_management.alert_disabled'));
    }

    public function test_it_can_enable_a_product(): void
    {
        $user = $this->userAdminCreate();
        $product = Product::factory()->deleted()->create();
        $response = $this->actingAs($user)->get(route('admin.products.enable', $product->id));

        $this->assertNotNull(Product::find($product->id));
        $response->assertRedirect();
        $response->assertSessionHas('success', trans('app.product_management.alert_enabled'));
    }

    public function test_it_show_the_edit_form_product(): void
    {
        $user = $this->userAdminCreate();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.products.edit', $product->id));

        $response->assertStatus(200);
        $response->assertSee(trans('app.product_management.edit_product'));
    }

    public function test_it_show_the_create_form_product(): void
    {
        $user = $this->userAdminCreate();
        $response = $this->actingAs($user)->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertSee(trans('app.product_management.create_product'));
    }
}
