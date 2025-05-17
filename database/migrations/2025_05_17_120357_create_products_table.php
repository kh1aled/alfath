<?php

use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignIdFor(Category::class , "category_id")->nullable(); // أو يمكن ربطه بجداول خارجية لاحقاً
            $table->integer("count")->default(0); // الكمية المتوفرة
            $table->integer('minimum_quantity')->default(0); // الحد الأدنى للكمية
            $table->string("unit")->default('kilo'); // وحدة القياس
            $table->decimal('buying_price', 8, 2); // سعر الشراء
            $table->decimal('selling_price', 8, 2); // سعر البيع
            $table->decimal('weight', 8, 2)->nullable(); // الوزن
            $table->string('image')->nullable(); // صورة المنتج
            $table->string('status')->default('active'); // active, inactive, discontinued
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
