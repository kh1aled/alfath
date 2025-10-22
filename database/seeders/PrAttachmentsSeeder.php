<?php

namespace Database\Seeders;

use App\Models\PrAttachments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrAttachmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        PrAttachments::factory()->count(10)->create();
    }
}
