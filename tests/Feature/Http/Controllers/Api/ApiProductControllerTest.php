<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use Tests\TestCase;
use Tests\Traits\ContextImageFake;
use TypeError;

class ApiProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use ContextImageFake;
    use MakesJsonApiRequests;

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeInstanceImage();
    }

    public function test_it_can_delete_a_image(): TestResponse
    {
        [$response, $product] = $this->createAndPostDeleteImage();

        $response->assertStatus(200);
        $this->assertSame(0, $product->fresh()->images->count());
        return $response;
    }

    public function test_it_can_delete_a_image_with_type_error(): void
    {
        $this->fakeInstanceImage(null, new TypeError());
        $this->test_it_can_delete_a_image();
    }

    public function test_it_can_delete_a_image_with_connection_errors(): void
    {
        $this->fakeInstanceImage(null, new ConnectException('', new Request('delete', '/')));
        [$response] = $this->createAndPostDeleteImage();
        $response->assertStatus(400);
    }

    public function test_it_can_delete_a_image_with_others_errors(): void
    {
        $this->fakeInstanceImage(null, new Exception());
        [$response] = $this->createAndPostDeleteImage();
        $response->assertStatus(400);
    }

    public function test_it_can_inactivate_a_user(): void
    {
        $userAdmin = $this->userAdminCreate();
        $user = $this->userClientCreate();
        $response = $this->actingAs($userAdmin)->post(route('api.users.setbanned', $user->id), ['banned_until' => 5]);

        $response->assertStatus(200);
        $this->assertSame(now()->addDays(5)->format('d/m/Y'), User::find($user->id)->banned_until->format('d/m/Y'));
    }

    public function test_it_can_not_inactive_an_user_with_admin_role(): void
    {
        $response = $this->executeAdminTest(['banned_until' => 5], 400);
        $response->assertStatus(400);
    }

    public function test_it_can_activate_an_user(): void
    {
        $user = $this->userClientCreate();
        $user->banned_until = now()->addDays(5);
        $user->save();

        $userAdmin = $this->userAdminCreate();
        $response = $this->actingAs($userAdmin)->post(route('api.users.setbanned', $user->id), ['banned_until' => null]);

        $response->assertStatus(200);
        $this->assertNull(User::find($user->id)->check_banned_until);
    }

    public function test_it_show_errors_when_is_invalid_the_post_data(): void
    {
        Notification::fake();
        $userAdmin = $this->userAdminCreate();
        $response = $this->executeAdminTest(['banned_untilxx' => 5], 'error');
        Notification::assertNotSentTo($userAdmin, SendBanUnbanNotification::class);
        $response->assertStatus(422);
    }

    public function test_it_can_read_all_categories(): void
    {
        $this->userAdminApiCreate();
        $categories = Category::get();

        $response = $this->jsonApi()->expects('categories')->get(route('v1.categories.index'));

        $response->assertStatus(200);
        $response->assertFetchedMany($categories);
    }

    public function test_it_can_read_a_category(): void
    {
        $this->userAdminApiCreate();
        $category = Category::first();

        $response = $this->jsonApi()->expects('categories')->get(route('v1.categories.show', $category->id));

        $response->assertStatus(200);
        $response->assertFetchedOne($category);
    }

    public function test_it_can_read_all_products(): void
    {
        $this->userAdminApiCreate();
        $products = Product::factory(3)->create();

        $response = $this->jsonApi()->expects('products')->get(route('v1.products.index'));

        $response->assertStatus(200);
        $response->assertFetchedMany($products);
    }

    public function test_it_can_search_a_product(): void
    {
        $this->userAdminApiCreate();
        $products = Product::factory(3)->create();

        $response = $this->jsonApi()->expects('products')->get(route('v1.products.search') . '?search=' . $products[0]->name);

        $response->assertStatus(200);
    }

    public function test_it_can_search_a_product_with_category(): void
    {
        $this->userAdminApiCreate();
        $products = Product::factory(3)->create();

        $response = $this->jsonApi()->expects('products')
                    ->get(route('v1.products.search') . '?filter[category_id]= ' . $products[0]->category_id . '&search=' . $products[0]->name);

        $response->assertStatus(200);
    }

    public function test_it_can_read_a_product(): void
    {
        $this->userAdminApiCreate();
        $product = Product::factory()->create();

        $response = $this->jsonApi()->expects('products')->get(route('v1.products.show', $product->id));

        $response->assertStatus(200);
        $response->assertFetchedOne($product);
    }

    public function test_it_can_create_a_product(): void
    {
        $this->userAdminApiCreate();
        $category = Category::select('id')->first();
        $data = [
                'type' => 'products',
                'attributes' => [
                    'name' => 'Computadora',
                    'description' => 'description',
                    'category_id' => $category->id,
                    'quantity' => 3,
                    'price' => '4.2',
                    ],
                ];

        $response = $this->jsonApi()->withData($data)->post(route('v1.products.store'));
        $response->assertStatus(201);
    }

    public function test_it_can_update_a_product(): void
    {
        $product = Product::factory()->create();
        $this->userAdminApiCreate();
        $category = Category::select('id')->first();
        $data = [
                'type' => 'products',
                'id' => "$product->id",
                'attributes' => [
                    'name' => 'test',
                    'description' => 'description',
                    'category_id' => $category->id,
                    'quantity' => 3,
                    'price' => '4.2',
                    ],
                ];

        $response = $this->jsonApi()->withData($data)->patch(route('v1.products.update', $product->id));
        $response->assertStatus(200);
    }

    public function test_it_can_delete_a_product(): void
    {
        $product = Product::factory()->create();
        $this->userAdminApiCreate();

        $response = $this->jsonApi()->delete(route('v1.products.destroy', $product->id));
        $response->assertStatus(204);
    }

    public function test_it_can_queue_products_export()
    {
        $admin = $this->userAdminCreate();
        $filename = 'products_' . now()->format('Y-m-d_H_i_s') . $admin->id . '.xlsx';
        $dir = config('report_directory');

        $response = $this->actingAs($admin)->post(route('admin.products.export'));

        $response->assertRedirect();
        Storage::assertExists($dir . $filename);
        Storage::delete($dir . $filename);
        Storage::assertMissing($dir . $filename);
    }

    public function test_it_can_download_export_file_correctly()
    {
        $filename = 'test.xlsx';
        $dir = config('report_directory');
        Storage::put(config('report_directory') . $filename, 'test');

        $response = $this->get(route('products.exportDownload', $filename));

        $response->assertOk();
        Storage::assertExists($dir . $filename);
        Storage::delete($dir . $filename);
        Storage::assertMissing($dir . $filename);
    }

    private function executeAdminTest(array $postData, int|string $assertStatus): TestResponse
    {
        $userAdmin = $this->userAdminCreate();
        $response = $this->actingAs($userAdmin)->post(route('api.users.setbanned', $userAdmin->id), $postData);

        $response->assertJson(['status' => $assertStatus]);
        return $response;
    }

    private function createAndPostDeleteImage(): array
    {
        $userAdmin = $this->userAdminCreate();
        $product = Product::factory()->create();
        $product->images()->createMany([
            ['url' => 'https://i.imgur.com/fakehash1.jpg', 'data' => ['deletehash' => 'fakeDeleteHash1']],
        ]);
        $image = $product->images->first();
        $response = $this->actingAs($userAdmin)->delete(route('api.images.destroy', $image->id));
        return [$response, $product];
    }
}
