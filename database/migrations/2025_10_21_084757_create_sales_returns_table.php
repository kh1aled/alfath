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
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('return_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status')->default('draft'); // draft, approved, refunded
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('sales_returns');
    }
};
