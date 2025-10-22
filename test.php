<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =========================
// 1) customers (if not exists)
// =========================
class CreateCustomersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable()->index();
                $table->string('phone')->nullable();
                $table->text('billing_address')->nullable();
                $table->text('shipping_address')->nullable();
                $table->string('tax_number')->nullable();
                $table->decimal('credit_limit', 15, 2)->default(0);
                $table->json('meta')->nullable(); // extensible
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}

// =========================
// 2) products (if not exists)
// =========================
class CreateProductsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('sku')->nullable()->index();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->decimal('cost', 15, 2)->default(0);
                $table->decimal('tax_rate', 5, 2)->default(0); // percentage
                $table->decimal('stock_qty', 20, 4)->default(0);
                $table->boolean('is_serialized')->default(false);
                $table->unsignedBigInteger('location_id')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['name']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}

// =========================
// 3) sales_quotes
// =========================
class CreateSalesQuotesTable extends Migration
{
    public function up()
    {
        Schema::create('sales_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->index();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->enum('status', ['draft','sent','accepted','rejected','expired','converted'])->default('draft');
            $table->date('valid_until')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_quotes');
    }
}

// =========================
// 4) sales_quote_items
// =========================
class CreateSalesQuoteItemsTable extends Migration
{
    public function up()
    {
        Schema::create('sales_quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained('sales_quotes')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('qty', 20, 4)->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_quote_items');
    }
}

// =========================
// 5) sales_orders
// =========================
class CreateSalesOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable()->index();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->enum('status', ['pending','confirmed','reserved','packed','shipped','invoiced','cancelled'])->default('pending');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('assigned_to')->nullable()->index(); // sales rep
            $table->json('shipping')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_orders');
    }
}

// =========================
// 6) sales_order_items
// =========================
class CreateSalesOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('sales_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('sales_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('qty', 20, 4)->default(1);
            $table->decimal('qty_reserved', 20, 4)->default(0);
            $table->decimal('qty_shipped', 20, 4)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_order_items');
    }
}

// =========================
// 7) invoices
// =========================
class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable()->index();
            $table->foreignId('order_id')->nullable()->constrained('sales_orders')->nullOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['draft','issued','paid','partial','cancelled'])->default('draft');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}

// =========================
// 8) payments
// =========================
class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('method')->nullable();
            $table->string('reference')->nullable();
            $table->date('paid_at')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('received_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

// =========================
// 9) returns (sales returns / credit notes)
// =========================
class CreateReturnsTable extends Migration
{
    public function up()
    {
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('sales_orders')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->decimal('refund_total', 15, 2)->default(0);
            $table->enum('status', ['pending','processed','rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sales_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('sales_returns')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('qty', 20, 4)->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_return_items');
        Schema::dropIfExists('sales_returns');
    }
}

// =========================
// 10) stock_movements (audit trail for stock changes)
// =========================
class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('change_qty', 20, 4);
            $table->decimal('balance_after', 20, 4)->nullable();
            $table->enum('type', ['sale','return','purchase','adjustment','transfer'])->index();
            $table->unsignedBigInteger('reference_id')->nullable()->index();
            $table->string('reference_type')->nullable(); // e.g. App\\Models\\SalesOrder
            $table->unsignedBigInteger('performed_by')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('performed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}

// =========================
// Optional: activity_logs (simple)
// =========================
class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('action');
                $table->morphs('subject'); // subject_type, subject_id
                $table->text('changes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}

// End of file
