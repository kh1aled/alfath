<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\SalesPaymentResource;
use App\Models\SalesPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SalesPaymentController extends Controller
{
    public function index()
    {
        $payments = SalesPayment::with(['invoice', 'customer'])->latest()->paginate(10);
        return SalesPaymentResource::collection($payments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:sales_invoices,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string',
            'reference' => 'nullable|string',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $validated['payment_number'] = 'PAY-' . strtoupper(Str::random(8));
        $validated['created_by'] = Auth::id();

        $payment = SalesPayment::create($validated);

        // ✅ Optional: update invoice total paid amount
        $invoice = $payment->invoice;
        $invoice->total_paid = ($invoice->total_paid ?? 0) + $payment->amount;
        $invoice->save();

        return new SalesPaymentResource($payment);
    }

    public function show(SalesPayment $salesPayment)
    {
        return new SalesPaymentResource($salesPayment->load(['invoice', 'customer']));
    }

    public function update(Request $request, SalesPayment $salesPayment)
    {
        $validated = $request->validate([
            'amount' => 'numeric|min:0.01',
            'payment_method' => 'nullable|string',
            'reference' => 'nullable|string',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $salesPayment->update([
            ...$validated,
            'updated_by' => Auth::id(),
        ]);

        return new SalesPaymentResource($salesPayment);
    }

    public function destroy(SalesPayment $salesPayment)
    {
        $salesPayment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}