<?php

namespace Database\Factories;

use App\Models\PurchaseRequisition;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'po_number'   => $this->faker->unique()->numerify('PO-2025-#####'),
            'pr_id'       => PurchaseRequisition::factory(),
            'supplier_id'   => Supplier::factory(),
            'order_date'  => $this->faker->date(),
            'status'      => $this->faker->randomElement(['draft', 'open', 'fulfilled', 'cancelled']),
            'currency'    => $this->faker->randomElement(['USD', 'EUR', 'EGP']),
            'payment_terms' => $this->faker->sentence(3),
            'tax'         => $this->faker->randomFloat(2, 0, 100),
            'discount'    => $this->faker->randomFloat(2, 0, 200),
            'total_amount' => $this->faker->randomFloat(2, 500, 5000),
            'created_by'  => User::factory(),
            'approved_by' => User::factory(),
        ];
    }
}
