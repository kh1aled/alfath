<?php

use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalesDashboardController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SalesPaymentController;
use App\Http\Controllers\SalesQuoteController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GoodReceiptController;
use App\Http\Controllers\PoApprovalController;
use App\Http\Controllers\PrApprovalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesInvoiceItemController;
use App\Http\Controllers\SalesQuoteItemController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});





Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/paginated', "indexWithPagination");
    Route::post('/store', 'store');
    Route::get('/pdf', "exportPdf");
    Route::get('/storage/{storage}', "indexWithStorage");
    Route::get('/{product}', 'show');
    Route::put('/{product}', 'update');
    Route::delete('/{product}', 'destroy');
});


Route::prefix('categories')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/{category}', 'show');
    Route::put('/{category}', 'update');
    Route::delete('/{category}', 'destroy');
});


Route::prefix('brands')->controller(BrandController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/{brand}', 'show');
    Route::put('/{brand}', 'update');
    Route::delete('/{brand}', 'destroy');
});


Route::prefix("customers")->controller(CustomerController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/pdf', 'exportPdf');
    Route::get('/{customer}', 'show');
    Route::put('/{customer}', 'update');
    Route::delete('/{customer}', 'destroy');
});


Route::prefix("suppliers")->controller(SupplierController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/pdf', 'exportPdf');
    Route::get('/{supplier}', 'show');
    Route::put('/{supplier}', 'update');
    Route::delete('/{supplier}', 'destroy');
});


Route::prefix("employees")->controller(EmployeeController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/{employee}', 'show');
    Route::put('/{employee}', 'update');
    Route::delete('/{employee}', 'destroy');
});


Route::prefix("users")->controller(UserController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/pdf', "exportPdf");
    Route::get('/{user}', 'show');
    Route::put('/{user}', 'update');
    Route::delete('/{user}', 'destroy');
});

Route::prefix("roles")->controller(RoleController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/pdf', "exportPdf");
    Route::get('/{role}', 'show');
    Route::put('/{role}', 'update');
    Route::delete('/{role}', 'destroy');
});


Route::prefix("warehouses")->controller(StorageController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/pdf', "exportPdf");
    Route::get('/{warehouse}', 'show');
    Route::put('/{warehouse}', 'update');
    Route::delete('/{warehouse}', 'destroy');
});


Route::prefix("transfers")->controller(TransferController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::get('/pdf', "exportPdf");
    Route::get('/{transfer}', 'show');
    Route::put('/{transfer}', 'update');
    Route::delete('/{transfer}', 'destroy');
});





Route::prefix('purchase-requisitions')->controller(PurchaseRequisitionController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/status/approved', 'indexApproved');
    Route::get('/paginated', "indexWithPagination");
    Route::post('/store', 'store');
    Route::get('/pdf', "exportPdf");
    Route::get('/{purchaseRequisition}', 'show');
    Route::put('/{purchaseRequisition}', 'update');
    Route::delete('/{purchaseRequisition}', 'destroy');
});


Route::prefix("approvals")->controller(PrApprovalController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/paginated', "indexWithPagination");
    Route::post('/store', 'store');
    Route::post('/{approval}/approve', 'approve');
    Route::post('/{approval}/reject', 'reject');
    Route::get('/pdf', "exportPdf");
    Route::get('/{approval}', 'show');
    Route::put('/{approval}', 'update');
    Route::delete('/{approval}', 'destroy');
});

Route::prefix("po-approvals")->controller(PoApprovalController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/paginated', "indexWithPagination");
    Route::post('/store', 'store');
    Route::post('/{PoApproval}/approve', 'approve');
    Route::post('/{PoApproval}/reject', 'reject');
    Route::get('/pdf', "exportPdf");
    Route::get('/{PoApproval}', 'show');
    Route::put('/{PoApproval}', 'update');
    Route::delete('/{PoApproval}', 'destroy');
});

Route::prefix("purchase-orders")->controller(PurchaseOrderController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/paginated', "indexWithPagination");
    Route::post('/store', 'store');
    Route::post('/{PurchaseOrder}/approve', 'approve');
    Route::post('/{PurchaseOrder}/reject', 'reject');
    Route::get('/pdf', "exportPdf");
    Route::get('/{PurchaseOrder}', 'show');
    Route::put('/{PurchaseOrder}', 'update');
    Route::delete('/{PurchaseOrder}', 'destroy');
});

Route::prefix("goods-receipts")->controller(GoodReceiptController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/paginated', "indexWithPagination");
    Route::post('/store', 'store');
    Route::post('/{GoodReceipt}/approve', 'approve');
    Route::post('/{GoodReceipt}/reject', 'reject');
    Route::get('/pdf', "exportPdf");
    Route::get('/{GoodReceipt}', 'show');
    Route::put('/{GoodReceipt}', 'update');
    Route::delete('/{GoodReceipt}', 'destroy');
});

Route::middleware('auth:sanctum')->prefix('sales')->controller(SaleController::class)->group(function () {
    Route::get('/',  'index');
    Route::post('/',  'store');
    Route::get('/{id}', 'show');
    Route::delete('/{id}', 'destroy');
});

//for test 
 Route::get('sales/summary/today', [SalesDashboardController::class, 'summaryToday']);


Route::middleware('auth:sanctum')->prefix('sales')->group(function () {
    Route::controller(SalesOrderController::class)->group(function () {
        Route::get('/orders', 'index');
        Route::post('/orders', 'store');
        Route::get('/orders/{id}', 'show');
        Route::put('/orders/{id}', 'update');
        Route::delete('/orders/{id}', 'destroy');
    });
});

Route::middleware(['auth:sanctum'])->controller(SalesQuoteController::class)->group(function () {
    Route::get('/sales/quotes', 'index');
    Route::post('/sales/quotes', 'store');
    Route::get('/sales/quotes/{id}', 'show');
    Route::put('/sales/quotes/{id}', 'update');
    Route::delete('/sales/quotes/{id}', 'destroy');
    Route::post('/sales/quotes/{id}/convert', 'convert');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/sales/quotes/items', [SalesQuoteItemController::class, 'store']);
    Route::put('/sales/quotes/items/{id}', [SalesQuoteItemController::class, 'update']);
    Route::delete('/sales/quotes/items/{id}', [SalesQuoteItemController::class, 'destroy']);
});





Route::apiResource('sales-orders', SalesOrderController::class);
Route::apiResource('sales-invoices', SalesInvoiceController::class);
Route::apiResource('sales-invoice-items', SalesInvoiceItemController::class)->only(['store', 'update', 'destroy']);
Route::apiResource('sales-payments', SalesPaymentController::class);

Route::apiResource('sales-returns', SalesReturnController::class);
Route::apiResource('stock-movements', StockMovementController::class);
