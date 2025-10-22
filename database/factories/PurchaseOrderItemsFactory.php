<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderItems>
 */
class PurchaseOrderItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = $this->faker->numberBetween(1, 20);
        $unitPrice = $this->faker->randomFloat(2, 50, 500);
        return [
            'po_id'       => PurchaseOrder::factory(),
            'item_code'   => $this->faker->unique()->numerify('ITEM-####'),
            'description' => $this->faker->sentence(6),
            'quantity'    => $qty,
            'unit'        => $this->faker->randomElement(['pcs', 'box', 'kg', 'ltr']),
            'unit_price'  => $unitPrice,
            'line_total'  => $qty * $unitPrice,
            'notes'       => $this->faker->optional()->sentence(),
        ];
    }
}
