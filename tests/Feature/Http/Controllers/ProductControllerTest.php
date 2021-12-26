<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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

    public function test_it_can_create_a_product(): void
    {
        $userAdmin = $this->userAdminCreate();
        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(100),
            'category_id' => Category::select('id')->inRandomOrder()->first()->id, // rand(1, 5),
            'price' => $this->faker->numberBetween(1000, 2500),
            'quantity' => $this->faker->numberBetween(0, 100),
        ];

        $response = $this->actingAs($userAdmin)->post(route('admin.products.store'), $data);

        $response->assertSessionHasNoErrors();
        $productCheck = Product::latest()->first();
        $this->assertSame($productCheck->name, $data['name']);
        $response->assertRedirect(route('admin.products.index'));
    }

    public function test_it_can_update_a_product(): void
    {
        $userAdmin = $this->userAdminCreate();
        $product = Product::factory()->create();
        $data = ['name' => 'New Name'];
        $response = $this->actingAs($userAdmin)->put(route('admin.products.update', $product->id), $data);

        $productCheck = Product::find($product->id);
        $response->assertSessionHasNoErrors();
        $this->assertSame($productCheck->name, $data['name']);
        $response->assertRedirect(route('admin.products.index'));
    }

    /**
     * @dataProvider usersDataProvider
     * @param string $field
     * @param mixed|null $value
     */
    public function test_it_show_errors_when_data_is_incorrect_in_update_product(string $field, $value = null): void
    {
        $userAdmin = $this->userAdminCreate();
        $product = Product::factory()->create();
        $data = [];
        $data[$field] = $value;
        $response = $this->actingAs($userAdmin)->put(route('admin.products.update', $product->id), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors($field);
    }

    public function usersDataProvider(): array
    {
        return [
            'Test the name is required' => ['name', null],
            'Test the name is too long' => ['name', Str::random(121)],
            'Test the category no exist' => ['category_id', 30],
            'Test the quantity is negative' => ['quantity', -1],
            'Test the price is not numeric' => ['category_id', 'four'],
            'Test the string is too long' => ['name', Str::random(2001)],
        ];
    }
}
