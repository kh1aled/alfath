<?php

namespace App\Http\Controllers;

use App\Http\Resources\SalesInvoiceResource;
use App\Models\SalesInvoice;
use App\Http\Requests\StoreSalesInvoiceRequest;
use App\Http\Requests\UpdateSalesInvoiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesInvoiceController extends Controller
{
    public function index()
    {
        $invoices = SalesInvoice::with('items')->latest()->paginate(10);
        return SalesInvoiceResource::collection($invoices);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:sales_invoices',
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
            'total_amount' => 'nullable|numeric',
        ]);

        $invoice = SalesInvoice::create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return new SalesInvoiceResource($invoice->load('items'));
    }

    public function show(SalesInvoice $salesInvoice)
    {
        return new SalesInvoiceResource($salesInvoice->load('items'));
    }

    public function update(Request $request, SalesInvoice $salesInvoice)
    {
        $validated = $request->validate([
            'customer_id' => 'exists:customers,id',
            'invoice_date' => 'date',
            'due_date' => 'nullable|date',
            'status' => 'string|nullable',
            'notes' => 'string|nullable',
            'total_amount' => 'numeric|nullable',
        ]);

        $salesInvoice->update([
            ...$validated,
            'updated_by' => Auth::id(),
        ]);

        return new SalesInvoiceResource($salesInvoice->load('items'));
    }

    public function destroy(SalesInvoice $salesInvoice)
    {
        $salesInvoice->delete();
        return response()->json(['message' => 'Invoice deleted successfully']);
    }
}
