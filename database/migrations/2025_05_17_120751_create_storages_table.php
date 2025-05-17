<?php

use App\Models\Employer;
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
        Schema::create('storages', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique(); // اسم المخزن
            $table->string("description")->nullable(); // وصف المخزن
            $table->string("location")->nullable(); // موقع المخزن
            $table->foreignIdFor(Employer::class,"manager_name")->nullable(); // اسم مدير المخزن
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storages');
    }
};
