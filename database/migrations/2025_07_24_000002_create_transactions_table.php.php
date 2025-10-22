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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_id')->constrained()->onDelete('cascade'); // معرف المخزن
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // معرف المنتج
            $table->enum('transaction_type', ['in', 'out', 'damage', 'inventory_adjustment', 'transfer']); // نوع الحركة (دخول، خروج، تلف، تعديل جرد، تحويل)
            $table->dateTime('transaction_date'); // التاريخ والوقت
            $table->integer('quantity'); // الكمية
            $table->text('description')->nullable(); // السبب أو الوصف
            $table->foreignId('employer_id')->constrained("employees")->onDelete('cascade'); // معرف الموظف الذي قام بالحركة
            $table->foreignId(column: 'supplier_id')->nullable()->constrained()->onDelete('cascade'); // معرف المورد (اختياري)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // معرف المستخدم (اختياري)
            $table->string('transaction_status')->default('pending'); // حالة الحركة (معلقة، مكتملة، ملغاة)
            $table->string('transaction_location')->nullable(); // موقع الحركة (اختياري)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
