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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade'); // المورد
            $table->dateTime('order_date'); // تاريخ الطلب
            $table->enum('status', ['completed', 'pending', 'canceled'])->default('pending'); // الحالة (تم، قيد الانتظار، ملغي)
            $table->foreignId('employer_id')->constrained('employers')->onDelete('cascade'); // الموظف المسؤول
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
