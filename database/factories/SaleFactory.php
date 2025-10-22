<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sale;
use App\Models\User;
use App\Models\Customer;
use App\Models\Storage;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Sale::class;

    public function definition(): array
    {
        $total = $this->faker->randomFloat(2, 100, 2000);
        $paid = $this->faker->randomFloat(2, 0, $total);
        $due = $total - $paid;

        return [
            'sale_number' => 'SAL-' . Str::upper(Str::random(6)),
            'customer_id' => Customer::inRandomOrder()->value('id'),
            'storage_id' => Storage::inRandomOrder()->value('id'),
            'total_amount' => $total,
            'paid_amount' => $paid,
            'due_amount' => $due,
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'bank_transfer']),
            'status' => $this->faker->randomElement(['completed', 'pending', 'cancelled']),
            'sale_date' => now(),
            'created_by' => User::inRandomOrder()->value('id'),
        ];
    }
}
