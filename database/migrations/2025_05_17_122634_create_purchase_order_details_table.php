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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('purchase_orders')->onDelete('cascade'); // علاقة بـ PurchaseOrder
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // علاقة بـ Product
            $table->integer('quantity'); // الكمية المطلوبة
            $table->decimal('purchase_price', 10, 2); // سعر الشراء
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_details');
    }
};
