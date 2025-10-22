<?php

namespace Database\Seeders;

use App\Models\ApprovalMatrix;
use Illuminate\Database\Seeder;

class ApprovalMatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ApprovalMatrix::create([
            'approver_id' => 113, // Ahmed
            'level' => 1,
            'min_amount' => 0,
            'max_amount' => 5000,
        ]);

        ApprovalMatrix::create([
            'approver_id' => 112, // Omar
            'level' => 2,
            'min_amount' => 5000,
            'max_amount' => null,
        ]);
    }
}
