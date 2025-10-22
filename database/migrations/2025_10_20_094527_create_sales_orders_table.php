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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('quote_id')->nullable(); //if request send from quoute
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->date('order_date')->nullable();
            $table->string('status')->default('pending'); // pending, confirmed, shipped, delivered, cancelled
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            // relations
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('sales_quotes')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
