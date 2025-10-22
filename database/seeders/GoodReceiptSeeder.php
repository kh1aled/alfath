<?php

namespace Database\Seeders;

use App\Models\GoodReceipt;
use App\Models\GoodReceiptItems;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoodReceiptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Create 10 receipts, each with random items
        GoodReceipt::factory(10)
            ->create()
            ->each(function ($receipt) {
                GoodReceiptItems::factory(rand(2, 5)) // each receipt has 2–5 items
                    ->create(['goods_receipt_id' => $receipt->id]);
            });
    }
}
