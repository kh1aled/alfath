<?php

namespace Database\Seeders;

use App\Models\PrApproval;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        PrApproval::factory()->count(10)->create();
    }
}
