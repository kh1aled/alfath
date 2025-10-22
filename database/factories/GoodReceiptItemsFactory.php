<?php

namespace Database\Factories;

use App\Models\GoodReceipt;
use App\Models\GoodReceiptItems;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoodReceiptItems>
 */
class GoodReceiptItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = GoodReceiptItems::class;

    public function definition(): array
    {
        return [
            'goods_receipt_id' => GoodReceipt::factory(), // Creates a related GoodsReceipt
            'item_id' => Product::factory(), // Creates a related Product
            'received_qty' => $this->faker->numberBetween(1, 100),
        ];
    }
}
