<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SalesInvoiceItemResource;
use App\Models\SalesInvoiceItem;
use Illuminate\Http\Request;

class SalesInvoiceItemController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:sales_invoices,id',
            'product_id' => 'required|exists:products,id',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0',
        ]);

        $subtotal = $validated['quantity'] * $validated['unit_price'];
        $discount = $validated['discount'] ?? 0;
        $tax = ($subtotal - $discount) * (($validated['tax_rate'] ?? 0) / 100);
        $total = $subtotal - $discount + $tax;

        $item = SalesInvoiceItem::create([
            ...$validated,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);

        return new SalesInvoiceItemResource($item);
    }

    public function update(Request $request, SalesInvoiceItem $salesInvoiceItem)
    {
        $validated = $request->validate([
            'quantity' => 'numeric|min:1',
            'unit_price' => 'numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0',
        ]);

        $data = array_merge($salesInvoiceItem->toArray(), $validated);

        $subtotal = $data['quantity'] * $data['unit_price'];
        $discount = $data['discount'] ?? 0;
        $tax = ($subtotal - $discount) * (($data['tax_rate'] ?? 0) / 100);
        $total = $subtotal - $discount + $tax;

        $salesInvoiceItem->update([
            ...$validated,
            'subtotal' => $subtotal,
            'total' => $total,
        ]);

        return new SalesInvoiceItemResource($salesInvoiceItem);
    }

    public function destroy(SalesInvoiceItem $salesInvoiceItem)
    {
        $salesInvoiceItem->delete();
        return response()->json(['message' => 'Invoice item deleted successfully']);
    }
}