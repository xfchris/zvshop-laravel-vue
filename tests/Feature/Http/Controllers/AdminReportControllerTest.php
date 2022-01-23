<?php

namespace Tests\Feature\Http\Controllers;

use App\Helpers\ReportHelper;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\PaymentGatewayFake;
use Tests\Traits\PaymentManagement;

class AdminReportControllerTest extends TestCase
{
    use RefreshDatabase;
    use PaymentGatewayFake;
    use PaymentManagement;

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
        $filename = 'general_' . ReportHelper::createReportName() . $admin->id . '.pdf';
        $path = config('constants.report_directory') . $filename;

        $response = $this->actingAs($admin)->post(route('admin.reports.general'), [
            'start_date' => '2020-01-01',
            'end_date' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        Storage::assertExists($path);
    }

    public function test_it_can_to_create_a_sales_report(): void
    {
        $products = Product::factory(2)->create(['quantity'=>10]);
        $this->generateOrdersApproved($products, 2);

        $admin = $this->userAdminCreate();
        $filename = 'sales_' . ReportHelper::createReportName() . $admin->id . '.pdf';
        $path = config('constants.report_directory') . $filename;

        $response = $this->actingAs($admin)->post(route('admin.reports.sales'), [
            'start_date' => '2020-01-01',
            'end_date' => now()->format('Y-m-d'),
            'category_id' => $products[0]->category->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        Storage::assertExists($path);
    }
}
