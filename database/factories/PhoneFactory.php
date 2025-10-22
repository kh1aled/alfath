<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Employer;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Phone>
 */
class PhoneFactory extends Factory
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
            "phone_number" => $this->faker->phoneNumber(),
            "phoneable_type" => Supplier::class,
            "phoneable_id" => Supplier::factory(),
        ];
    }
}
