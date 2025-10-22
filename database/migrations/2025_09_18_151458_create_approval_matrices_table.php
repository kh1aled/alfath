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
        Schema::create('approval_matrices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->string('role');
            $table->unsignedInteger('level');
            $table->decimal('min_amount', 12, 2)->default(0);
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_matrices');
    }
};
