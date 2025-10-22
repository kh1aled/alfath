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
        
        Schema::table('good_receipts', function (Blueprint $table) {
            $table->enum('status', ['draft', 'partial', 'completed'])->default('draft')->after('receipt_date');
            $table->string('invoice_image')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('good_receipts', function (Blueprint $table) {
            $table->dropColumn(['status', 'invoice_image']);
        });
    }
};
