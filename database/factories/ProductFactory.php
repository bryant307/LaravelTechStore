<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->numberBetween(10000, 99999),
            'name' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'image_path' => 'products/' . $this->faker->image('public/storage/products', 640, 480, null, false),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'subcategory_id' => function () {
                return \App\Models\Subcategory::inRandomOrder()->first()->id;
            },
            'stock' => $this->faker->numberBetween(5, 100),
            'track_inventory' => true,
            'available' => true,
            'low_stock_threshold' => 5,
        ];
    }
}
