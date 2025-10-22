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
            $table->string('po_number', 50)->unique();

            $table->foreignId('pr_id')
                ->constrained('purchase_requisitions')
                ->onDelete('cascade');

            $table->foreignId("supplier_id")
                ->constrained('suppliers')
                ->onDelete('cascade');

            $table->date("order_date");
            $table->enum('status', ["draft", "open", "fulfilled", "cancelled" , 'partial'])->default('draft');

            $table->string("currency", 10)->nullable();
            $table->string("payment_terms")->nullable();

            $table->decimal("tax", 10, 2)->default(0);
            $table->decimal("discount", 10, 2)->default(0);
            $table->decimal("total_amount", 15, 2)->default(0);

            $table->foreignId("created_by")->constrained('users')->onDelete('cascade');
            $table->foreignId("approved_by")->nullable()->constrained('users')->nullOnDelete();

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
