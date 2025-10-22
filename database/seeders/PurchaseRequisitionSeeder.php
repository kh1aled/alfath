<?php

namespace Database\Seeders;

use App\Models\PurchaseRequisition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseRequisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        PurchaseRequisition::factory()->count(10)->create();
    }
}
