<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company;

        return [
            'name' => $name,
            'code' => strtoupper(substr($name, 0, 3)) . '-' . $this->faker->unique()->numberBetween(100, 999),
            'image' => null,
            'country' => $this->faker->country,
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'website' => $this->faker->url,
        ];
    }
}
