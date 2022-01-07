<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductObserver
{
    public function updating(Product $product): void
    {
        if ($product->isDirty('quantity')) {
            DB::select('CALL change_orders_products_quantity(' . $product->id . ', ' . $product->quantity . ')');
        }
    }
}
