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
            //
            $table->foreignId("received_by")->after('invoice_image')->constrained("users")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('good_receipts', function (Blueprint $table) {
            $table->dropColumn("good_receipts");
        });
    }
};
