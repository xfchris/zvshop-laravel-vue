<?php

namespace App\Filters\ModelFilters;

use App\Filters\Conditions\CategoryId;
use App\Filters\Filter;
use App\Models\Product;

class ProductFilters extends Filter
{
    protected string $model = Product::class;
    protected array $applicableConditions = [
        'categoryId' => CategoryId::class,
    ];

    protected function joins(): Filter
    {
        $this->query->with('category:id,name', 'images');
        return $this;
    }
}
