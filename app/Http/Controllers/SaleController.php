<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['customer', 'warehouse', 'items'])->orderByDesc('id')->paginate(20);
        return SaleResource::collection($sales);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($data) {
            $saleNumber = 'POS-' . now()->format('YmdHis');

            $total = collect($data['items'])->sum(fn($i) => $i['price'] * $i['quantity']);

            $sale = Sale::create([
                'sale_number' => $saleNumber,
                'customer_id' => $data['customer_id'] ?? null,
                'warehouse_id' => $data['warehouse_id'],
                'total_amount' => $total,
                'paid_amount' => $total,
                'due_amount' => 0,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'status' => 'completed',
                'created_by' => Auth::id(),
            ]);

            foreach ($data['items'] as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                // Update stock movements
                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $sale->warehouse_id,
                    'quantity' => -$item['quantity'],
                    'movement_type' => 'sale',
                    'reference_id' => $sale->id,
                    'reference_type' => 'sales',
                    'notes' => 'POS Sale',
                    'created_by' => $sale->created_by,
                ]);
            }

            return new SaleResource($sale->load(['items', 'customer', 'warehouse']));
        });
    }

    public function show($id)
    {
        $sale = Sale::with(['items.product', 'customer', 'warehouse'])->findOrFail($id);
        return new SaleResource($sale);
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();
        return response()->json(['message' => 'Sale deleted successfully.']);
    }
}