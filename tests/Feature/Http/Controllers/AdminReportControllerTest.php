<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\CustomAsserts;
use Tests\Traits\PaymentGatewayFake;
use Tests\Traits\PaymentManagement;

class AdminReportControllerTest extends TestCase
{
    use RefreshDatabase;
    use PaymentGatewayFake;
    use PaymentManagement;
    use CustomAsserts;

    public function test_it_show_the_report_lists(): void
    {
        $user = $this->userAdminCreate();
        $response = $this->actingAs($user)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee(trans('app.reports.title'));
    }

    public function test_it_can_to_create_a_general_report(): void
    {
        $admin = $this->userAdminCreate();
        Product::factory(2)->create();

        $response = $this->actingAs($admin)->post(route('admin.reports.general'), [
            'start_date' => '2020-01-01',
            'end_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertFileStorageExists(config('constants.report_directory') . 'general_*' . $admin->id . '.pdf');
    }

    public function test_it_can_to_create_a_sales_report(): void
    {
        $products = Product::factory(2)->create(['quantity'=>10]);
        $this->generateOrdersApproved($products, 2);

        $admin = $this->userAdminCreate();

        $response = $this->actingAs($admin)->post(route('admin.reports.sales'), [
            'start_date' => '2020-01-01',
            'end_date' => now()->format('Y-m-d'),
            'category_id' => $products[0]->category->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertFileStorageExists(config('constants.report_directory') . 'sales_*' . $admin->id . '.pdf');
    }
}
