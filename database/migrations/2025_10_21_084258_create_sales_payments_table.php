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
        Schema::create('sales_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->nullable();
            $table->string('reference')->nullable();
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            // Relationships
            $table->foreign('invoice_id')->references('id')->on('sales_invoices')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_payments');
    }
};
