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
        Schema::create('sales_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->date('quote_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('draft');
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();

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
        Schema::dropIfExists('sales_quotes');
    }
};
