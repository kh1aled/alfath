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
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->date('invoice_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('unpaid'); // unpaid, paid, partially_paid, cancelled
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('sales_orders')->onDelete('set null');
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
        Schema::dropIfExists('sales_invoices');
    }
};
