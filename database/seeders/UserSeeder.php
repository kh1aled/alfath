<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $password = Hash::make('mahmoud123@#$');

        // 🧑 Omar Mahmoud
        User::create([
            'name' => "Omar Mahmoud",
            'username' => 'Omar',
            'avatar' => "header-profile.svg",
            'address' => 'Bani Mazar',
            'email' => 'omarMahmoud@gmail.com',
            'email_verified_at' => now(),
            'password' => $password,
            'role_id' => Role::where('name', 'approver')->first()->id ?? Role::inRandomOrder()->first()->id,
            'remember_token' => Str::random(10),
        ]);

        // 🧑 Mohamed Ali
        User::create([
            'name' => "Mohamed Ali",
            'username' => 'Mohamed',
            'avatar' => "header-profile.svg",
            'address' => 'Minya',
            'email' => 'mohamedAli@gmail.com',
            'email_verified_at' => now(),
            'password' => $password,
            'role_id' => Role::where('name', 'approver')->first()->id ?? Role::inRandomOrder()->first()->id,
            'remember_token' => Str::random(10),
        ]);
    }
}
