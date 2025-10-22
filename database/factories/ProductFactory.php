<?php

namespace Database\Factories;

use App\Models\Category;
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
            //
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'category_id' => Category::inRandomOrder()->value('id'), // أو null لو مفيش تصنيفات
            'count' => $this->faker->numberBetween(0, 100),
            'minimum_quantity' => $this->faker->numberBetween(0, 10),
            'unit' => $this->faker->randomElement(['kilo', 'liter', 'piece']),
            'buying_price' => $this->faker->randomFloat(2, 5, 100),
            'selling_price' => $this->faker->randomFloat(2, 10, 150),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'image' => $this->faker->imageUrl(640, 480, 'product', true),
            'status' => $this->faker->randomElement(['active', 'inactive', 'discontinued']),
        ];
    }
}
