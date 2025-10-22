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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('storage_id')->constrained('storages')->cascadeOnDelete();
            $table->decimal('quantity', 15, 2);
            $table->enum('movement_type', [
                'purchase',
                'sale',
                'return',
                'transfer_in',
                'transfer_out',
                'adjustment'
            ]);
            $table->unsignedBigInteger('reference_id')->nullable(); // e.g. order_id or transfer_id
            $table->string('reference_type')->nullable(); // e.g. "sales_orders", "transfers"
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
