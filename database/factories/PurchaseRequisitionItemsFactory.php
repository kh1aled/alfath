<?php

namespace Database\Factories;

use App\Models\PurchaseRequisition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseRequisitionItems>
 */
class PurchaseRequisitionItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(2, 1, 100); // رقم عشوائي 1 - 100
        $price = $this->faker->randomFloat(2, 10, 500); // سعر عشوائي 10 - 500
        $total = $quantity * $price;

        return [
            'pr_id' => PurchaseRequisition::factory(), // هيعمل PR لو مفيش موجود
            'item_code' => strtoupper($this->faker->bothify('ITEM-####')),
            'description' => $this->faker->sentence(6),
            'quantity' => $quantity,
            'unit' => $this->faker->randomElement(['pcs', 'kg', 'ltr', 'box']),
            'estimated_price' => $price,
            'total_estimated' => $total,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
