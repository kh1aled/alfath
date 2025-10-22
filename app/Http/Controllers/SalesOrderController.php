<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Http\Requests\StoreSalesOrderRequest;
use App\Http\Requests\UpdateSalesOrderRequest;
use App\Http\Controllers\Controller;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SalesOrderResource;

class SalesOrderController extends Controller
{
    public function index()
    {
        $orders = SalesOrder::with(['customer', 'items.product', 'creator'])->latest()->paginate(15);
        return SalesOrderResource::collection($orders);
    }

    public function show($id)
    {
        $order = SalesOrder::with(['customer', 'items.product', 'creator'])->findOrFail($id);
        return new SalesOrderResource($order);
    }

    public function store(StoreSalesOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['created_by'] = Auth::id();

            $order = SalesOrder::create($data);

            foreach ($request->items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price']
                ]);
            }

            $order->update(['total_amount' => $order->items->sum('total_price')]);
            DB::commit();

            return response()->json([
                'message' => 'Sales Order created successfully',
                'data' => new SalesOrderResource($order)
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateSalesOrderRequest $request, $id)
    {
        $order = SalesOrder::findOrFail($id);
        DB::beginTransaction();
        try {
            $order->update($request->validated());

            if ($request->has('items')) {
                $order->items()->delete();
                foreach ($request->items as $item) {
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['quantity'] * $item['unit_price']
                    ]);
                }
            }

            $order->update(['total_amount' => $order->items->sum('total_price')]);
            DB::commit();

            return response()->json([
                'message' => 'Sales Order updated successfully',
                'data' => new SalesOrderResource($order)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $order = SalesOrder::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Sales Order deleted successfully']);
    }

    public function changeStatus(Request $request, $id)
    {
        $order = SalesOrder::findOrFail($id);
        $request->validate(['status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled']);
        $order->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated successfully', 'status' => $order->status]);
    }
}
