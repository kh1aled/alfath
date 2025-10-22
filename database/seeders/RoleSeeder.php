<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        
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

        foreach ($roles as $key => $role) {
            Role::factory()->create(["name" => $role]);
        }
    }
}
