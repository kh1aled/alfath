<?php

namespace Database\Factories;

use App\Models\PurchaseRequisition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrAttachments>
 */
class PrAttachmentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileTypes = ['pdf', 'image', 'excel'];

        return [
            'pr_id' => PurchaseRequisition::factory(),
            'file_path' => 'uploads/pr_attachments/' . $this->faker->uuid . '.' . $this->faker->fileExtension(),
            'file_type' => $this->faker->randomElement($fileTypes),
            'uploaded_by' => User::factory(),
            'uploaded_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
