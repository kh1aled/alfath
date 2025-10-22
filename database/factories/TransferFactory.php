<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Storage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transfer>
 */
class TransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate a semi-unique reference string with timestamp and random number
        $reference = 'S-' . now()->format('ymdHis') . rand(100, 999);

        // Pick a random source storage, or create one if none exists
        $fromWarehouse = Storage::inRandomOrder()->first() ?? Storage::factory()->create();

        // Pick a different destination storage, or create one if needed
        $toWarehouse = Storage::where('id', '!=', $fromWarehouse->id)->inRandomOrder()->first()
            ?? Storage::factory()->create();

        // Pick or create a random product to transfer
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        // Creator of the transfer (required)
        $creator = User::inRandomOrder()->first() ?? User::factory()->create();

        // Optional authorizer (might be null to simulate pending approval)
        $authorizer = User::inRandomOrder()->first();

        return [
            'reference' => $reference,
            'date' => $this->faker->date(), // transfer date
            'from_storage_id' => $fromWarehouse->id, // source warehouse
            'to_storage_id' => $toWarehouse->id, // destination warehouse
            'product_id' => $product->id, // product being moved
            'quantity' => $this->faker->numberBetween(1, 100), // amount to transfer
            'reason' => $this->faker->sentence(), // reason for transfer
            'notes' => $this->faker->optional()->sentence(), // optional notes
            'authorized_by' => $authorizer?->id, // approver, can be null for unapproved
            'status' => $this->faker->randomElement([
                'pending',
                'rejected',
                'completed',
            ]), // current status
            'created_by' => $creator->id, // who created the transfer
        ];
    }
}
