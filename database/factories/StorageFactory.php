<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Storage>
 */
class StorageFactory extends Factory
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
            'name' => 'Warehouse - ' . $this->faker->city,
            'description' => $this->faker->sentence(),
            'location' => $this->faker->city,
        ];
    }
}
