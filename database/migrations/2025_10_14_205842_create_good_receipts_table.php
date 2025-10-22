<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('good_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->dateTime('receipt_date');
            $table->foreignId("received_by")->constrained("users")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('good_receipts');
    }
};
