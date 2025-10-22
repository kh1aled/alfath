<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductStorage>
 */
class ProductStorageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "product_id" => Product::inRandomOrder()->first()->id ?? Product::factory(),
            "storage_id" => Storage::inRandomOrder()->first()->id ?? Storage::factory(),
            "quantity" =>  $this->faker->numberBetween(1, 100),

        ];
    }
}
