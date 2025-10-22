<?php

namespace Database\Factories;

use App\Models\SalesQuote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesQuote>
 */
class SalesQuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = SalesQuote::class;

    public function definition()
    {
        return [
            'reference' => 'Q-' . $this->faker->unique()->numerify('#####'),
            'customer_id' => 1,
            'status' => 'draft',
            'valid_until' => now()->addDays(30),
            'subtotal' => 0,
            'discount' => 0,
            'tax' => 0,
            'total' => 0,
            'created_by' => 1,
        ];
    }
}
