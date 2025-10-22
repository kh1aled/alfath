<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->userName,
            'password' => bcrypt('password'), // ممكن تحط Password ثابت أو Random
            'role' => $this->faker->randomElement(['cashier', 'manager', 'storekeeper']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'address' => $this->faker->address,
            'date_of_birth' => $this->faker->date(),
            'hire_date' => $this->faker->date(),
            'salary' => $this->faker->randomFloat(2, 3000, 15000),
            'photo' => null,
        ];
    }
}
