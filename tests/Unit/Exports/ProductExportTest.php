<?php

namespace Tests\Unit\Exports;

use App\Exports\ProductExport;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductExportTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_it_can_map_a_product_to_export(): void
    {
        $product = Product::factory()->create();
        $export = new ProductExport();

        $response = $export->map($product);
        $this->assertSame($product->name, $response[1]);
        $this->assertSame($product->category->name, $response[3]);
    }
}
