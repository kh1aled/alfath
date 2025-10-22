<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalesReturnResource;
use App\Models\SalesReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SalesReturnController extends Controller
{
    public function index()
    {
        $returns = SalesReturn::with('items')->latest()->paginate(10);
        return SalesReturnResource::collection($returns);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:sales_invoices,id',
            'customer_id' => 'required|exists:customers,id',
            'return_date' => 'nullable|date',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.reason' => 'nullable|string',
        ]);

        $total = 0;
        foreach ($validated['items'] as $item) {
            $total += $item['quantity'] * $item['unit_price'];
        }

        $return = SalesReturn::create([
            'return_number' => 'RET-' . strtoupper(Str::random(8)),
            'invoice_id' => $validated['invoice_id'],
            'customer_id' => $validated['customer_id'],
            'return_date' => $validated['return_date'] ?? now(),
            'total_amount' => $total,
            'status' => $validated['status'] ?? 'draft',
            'notes' => $validated['notes'] ?? null,
            'created_by' => Auth::id(),
        ]);

        foreach ($validated['items'] as $item) {
            $return->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['quantity'] * $item['unit_price'],
                'reason' => $item['reason'] ?? null,
            ]);
        }

        return new SalesReturnResource($return->load('items'));
    }

    public function show(SalesReturn $salesReturn)
    {
        return new SalesReturnResource($salesReturn->load('items'));
    }

    public function update(Request $request, SalesReturn $salesReturn)
    {
        $validated = $request->validate([
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $salesReturn->update([
            ...$validated,
            'updated_by' => Auth::id(),
        ]);

        return new SalesReturnResource($salesReturn->load('items'));
    }

    public function destroy(SalesReturn $salesReturn)
    {
        $salesReturn->delete();
        return response()->json(['message' => 'Sales return deleted successfully']);
    }
}
