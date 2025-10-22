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
        Schema::create('pr_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_id')
                ->constrained('purchase_requisitions')
                ->onDelete('cascade');

            $table->string('file_path', 255);
            $table->string('file_type', 50);

            $table->foreignId('uploaded_by')
                ->constrained('users');

            $table->dateTime('uploaded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_attachments');
    }
};
