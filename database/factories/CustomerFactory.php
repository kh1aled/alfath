<?php

namespace Database\Factories;

use App\Models\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
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
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'zip_code' => $this->faker->postcode(),
        ];
    }
}
