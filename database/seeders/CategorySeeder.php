<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (Category::count()) {
            return;
        }
        Category::insert([
            ['name' => 'Phones', 'slug' => 'phones'],
            ['name' => 'Tablets', 'slug' => 'tablets'],
            ['name' => 'Laptops', 'slug' => 'laptos'],
            ['name' => 'Smart Watch', 'slug' => 'smartwatch'],
            ['name' => 'Smart TV', 'slug' => 'smartv'],
            ['name' => 'Others products', 'slug' => 'others-products'],
        ]);
    }
}
