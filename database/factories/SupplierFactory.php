<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'email'    => $this->faker->unique()->safeEmail,
            'address'  => $this->faker->address,
            'country'  => $this->faker->country,
            'city'     => $this->faker->city,
            'zip_code' => $this->faker->postcode,
        ];
    }
}
