<?php

use App\Models\Product;
use App\Models\Storage;
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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();

            $table->string('reference')->unique();
            $table->date('date');

            // transfer from storage to other storage
            $table->foreignIdFor(Storage::class, 'from_storage_id')
                  ->constrained('storages')
                  ->cascadeOnDelete();
            $table->foreignIdFor(Storage::class, 'to_storage_id')
                  ->constrained('storages')
                  ->cascadeOnDelete();

            // product and quantity 
            $table->foreignIdFor(Product::class)
                  ->constrained('products')
                  ->cascadeOnDelete();
            $table->unsignedInteger('quantity');

            $table->string('reason');
            $table->text('notes')->nullable();

            $table->foreignIdFor(User::class, 'authorized_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->enum('status', ['pending', 'rejected', 'completed'])
                  ->default('pending');

            $table->foreignIdFor(User::class, 'created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
