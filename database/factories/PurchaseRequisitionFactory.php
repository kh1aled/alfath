<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseRequisition>
 */
class PurchaseRequisitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('PR-#####'),
            'requester_id' => User::factory(),
            'priority' => $this->faker->randomElement(['low', 'normal', 'high']),
            'needed_by_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'purpose' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'converted_to_PO']),
            'notes' => $this->faker->optional()->sentence(),
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }
}
