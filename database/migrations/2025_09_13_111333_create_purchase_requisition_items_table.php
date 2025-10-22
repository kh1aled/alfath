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
        Schema::create('purchase_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_id')->constrained('purchase_requisitions')->onDelete('cascade');
            $table->string('item_code', 50)->nullable();
            $table->text("description")->nullable();
            $table->decimal('quantity', 12, 2);
            $table->string('unit', 50);
            $table->decimal('estimated_price', 12, 2)->nullable();
            $table->decimal('total_estimated', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisition_items');
    }
};
