<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $roles = [
            'admin',
            'store_manager',
            'inventory_clerk',
            'purchasing_agent',
            'sales_rep',
            'accountant',
            'auditor',
            'supplier',
            'customer',
            'data_entry',
            'viewer',
        ];

        return [
            //
            "name" => fake()->unique()->randomElement($roles)
        ];
    }
}
