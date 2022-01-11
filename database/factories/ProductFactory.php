<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(100),
            'category_id' => Category::select('id')->inRandomOrder()->first()->id, // rand(1, 5),
            'price' => $this->faker->numberBetween(10, 250),
            'quantity' => $this->faker->numberBetween(1, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function deleted(): Factory
    {
        return $this->state(function () {
            return [
                'deleted_at' => now(),
            ];
        });
    }
}
