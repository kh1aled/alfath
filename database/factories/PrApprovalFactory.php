<?php

namespace Database\Factories;

use App\Models\PurchaseRequisition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrApproval>
 */
class PrApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        //         Schema::create('pr_approvals', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('pr_id')
        //         ->constrained('purchase_requisitions')
        //         ->onDelete('cascade');
        //     $table->foreignId('approver_id')
        //         ->constrained('users');
        //     $table->enum('status', ['approved', 'rejected', 'pending'])->default('pending');
        //     $table->text('comments')->nullable();

        //     $table->dateTime('approved_at')->nullable();

        //     $table->timestamps();
        // });
        $status = $this->faker->randomElement(['approved', 'rejected', 'pending']);

        return [
            'pr_id' => PurchaseRequisition::factory(), 
            'approver_id' => User::factory(),
            'status' => $status,
            'comments' => $this->faker->optional()->sentence(),
            'approved_at' => $status !== 'pending' ? $this->faker->dateTimeThisYear() : null,
        ];
    }
}
