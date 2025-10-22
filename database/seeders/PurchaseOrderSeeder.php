<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $po = \App\Models\PurchaseOrder::factory()
            ->has(\App\Models\PurchaseOrderItems::factory()->count(5), 'items')
            ->create();
    }
}
