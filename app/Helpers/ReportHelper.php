<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ReportHelper
{
    public static function groupHistoryProducts(Collection $salesProducts): Collection
    {
        $products = [];
        foreach ($salesProducts as $saleProducts) {
            foreach ($saleProducts->products as $product) {
                $id = $product['pivot']['product_id'];

                $quantity = $product['pivot']['quantity'];
                $price = $product['price'];

                if (isset($products[$id])) {
                    $products[$id]['quantity'] += $products[$id]['quantity'];
                    $products[$id]['totalPrice'] += $products[$id]['totalPrice'];
                } else {
                    $products[$id] = [
                        'id' => $id,
                        'name' => $product['name'],
                        'quantity' => $quantity,
                        'totalPrice' => $price,
                    ];
                }
            }
        }
        self::addCategoriesToProducts($products);
        return new Collection($products);
    }

    public static function addCategoriesToProducts(array &$products): void
    {
        $productsCategories = Product::withTrashed()->select('id', 'category_id')->with('category:id,name')->find(array_keys($products));
        foreach ($productsCategories as $productCategory) {
            $products[$productCategory->id]['category_id'] = $productCategory->category_id;
            $products[$productCategory->id]['category'] = $productCategory->category->toArray();
        }
    }

    public static function rangeDate(array $request): array
    {
        return [$request['start_date'], $request['end_date'] . ' 23:59:59'];
    }

    public static function randomNameReports(): string
    {
        return now()->format('Y-m-d_H_i_s');
    }
}
