<?php

namespace Database\Factories;

use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SalesInvoiceFactory extends Factory
{
    protected $model = SalesInvoice::class;

    public function definition(): array
    {
        // 🎲 Pick related models randomly
        $order = SalesOrder::inRandomOrder()->first();
        $customer = $order?->customer_id ?? Customer::inRandomOrder()->value('id');
        $creator = User::inRandomOrder()->value('id');

        // 🧮 Generate amounts
        $total = $this->faker->randomFloat(2, 100, 2000);
        $paid = $this->faker->randomFloat(2, 0, $total);
        $status = $paid >= $total ? 'paid' : ($paid == 0 ? 'unpaid' : 'partial');

        // 📅 Dates
        $invoiceDate = $this->faker->dateTimeBetween('-5 days', 'now');
        $dueDate = (clone $invoiceDate)->modify('+7 days');

        return [
            'invoice_number' => 'INV-' . Str::upper(Str::random(6)), // Unique invoice code
            'order_id' => $order?->id,
            'customer_id' => $customer,
            'created_by' => $creator,
            'updated_by' => $creator,
            'invoice_date' => Carbon::instance($invoiceDate),
            'due_date' => Carbon::instance($dueDate),
            'status' => $status,
            'total_amount' => $total,
            'paid_amount' => $paid,
            'notes' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
