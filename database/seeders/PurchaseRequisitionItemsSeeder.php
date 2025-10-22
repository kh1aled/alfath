<?php

namespace Database\Seeders;

use App\Models\PurchaseRequisitionItems;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseRequisitionItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        PurchaseRequisitionItems::factory()->count(10)->create();
    }
}
