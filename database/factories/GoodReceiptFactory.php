<?php

namespace Database\Factories;

use App\Models\GoodReceipt;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoodReceipt>
 */
class GoodReceiptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = GoodReceipt::class;
    public function definition(): array
    {
        return [
            'po_id' => PurchaseOrder::factory(), // Creates a related Purchase Order
            'supplier_id' => Supplier::factory(), // Creates a related Supplier
            'receipt_date' => $this->faker->dateTimeThisYear(),
            'received_by' => User::factory(), // Creates a related User
            'invoice_image' => null,
            'status' => $this->faker->randomElement(['draft', 'completed', 'partial']),
        ];
    }
}
