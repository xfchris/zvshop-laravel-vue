<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ContextImageFake;

class StoreProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use ContextImageFake;

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeInstanceImage();
    }

    public function test_it_show_the_store_products(): void
    {
        $user = $this->userClientCreate();
        $response = $this->actingAs($user)->get(route('store.products.index'));

        $response->assertStatus(200);
        $response->assertSee(trans('app.online_store.title'));
    }

    public function test_it_show_the_store_products_by_category(): void
    {
        $user = $this->userClientCreate();
        $slug = Category::first()->slug;
        $response = $this->actingAs($user)->get(route('store.products.index', $slug));

        $response->assertStatus(200);
        $response->assertSee($slug);
    }

    public function test_it_search_the_store_products(): void
    {
        $user = $this->userClientCreate();
        $q = 'Iphone 11';
        $response = $this->actingAs($user)->get(route('store.products.index', ['q' => $q]));

        $response->assertStatus(200);
        $response->assertSee($q);
    }

    public function test_it_show_the_products_details(): void
    {
        $user = $this->userClientCreate();
        $product = Product::factory()->create();
        $product->images()->createMany([
            ['url' => 'https://i.imgur.com/fakehash1.jpg'],
            ['url' => 'https://i.imgur.com/fakehash2.jpg'],
        ]);
        $response = $this->actingAs($user)->get(route('store.products.show', $product->id));

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->description);
    }
}
