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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // الاسم التجاري
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->string('address')->nullable(); // العنوان
            $table->timestamps();
        });


//         SupplierID (مفتاح أساسي)

// الاسم التجاري

// رقم الهاتف

// البريد الإلكتروني

// العنوان
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
