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
        Schema::create('inventory_audits', function (Blueprint $table) {
            $table->id();
            $table->date('audit_date'); // التاريخ
            $table->foreignId('product_id')->constrained('products'); // المنتج
            $table->integer('actual_quantity'); // الكمية الفعلية
            $table->integer('expected_quantity'); // الكمية المتوقعة
            $table->integer('differences'); // الفروقات
            $table->text('notes')->nullable(); // الملاحظات
            $table->foreignId('employer_id')->constrained('employers'); // الموظف المسؤول
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_audits');
    }
};
