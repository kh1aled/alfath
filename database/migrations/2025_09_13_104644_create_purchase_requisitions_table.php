<?php

use App\Models\User;
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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->foreignId('requester_id')->constrained('users');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->date('needed_by_date');
            $table->text('purpose');
            $table->enum('status', ['pending', 'approved', 'rejected', 'converted_to_PO'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};
