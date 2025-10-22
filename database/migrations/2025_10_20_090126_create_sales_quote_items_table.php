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
        Schema::create('sales_quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained('sales_quotes')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('qty', 15, 4);
            $table->decimal('unit_price', 15, 4);
            $table->decimal('discount', 15, 4)->default(0);
            $table->decimal('tax_amount', 15, 4)->default(0);
            $table->decimal('line_total', 15, 4)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_quote_items');
    }
};
