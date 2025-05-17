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
        Schema::create('purchase_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete(); // معرف طلب الشراء
            $table->foreignId('product_id')->constrained()->cascadeOnDelete(); // معرف المنتج
            $table->integer('quantity'); // الكمية المطلوبة
            $table->decimal('unit_price', 10, 2); // سعر الوحدة
            $table->decimal('total_price', 10, 2); // السعر الإجمالي
            $table->text('note')->nullable(); // ملاحظات إضافية
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_products');
    }
};
