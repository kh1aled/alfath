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
        Schema::table('purchase_order_items', function (Blueprint $table) {
            // Add product_id column and relationship if it doesn’t exist
            if (!Schema::hasColumn('purchase_order_items', 'product_id')) {
                $table->foreignId('product_id')
                    ->nullable()
                    ->constrained('products')
                    ->onDelete('cascade')
                    ->after('po_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_order_items', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
        });
    }
};
