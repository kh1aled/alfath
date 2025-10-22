<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalesQuoteRequest;
use App\Http\Requests\UpdateSalesQuoteRequest;
use App\Http\Resources\SalesQuoteResource;
use App\Models\SalesQuote;
use App\Models\SalesQuoteItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesQuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SalesQuote::with(['customer', 'items.product', 'creator']);

        if ($request->has('customer_id')) $query->where('customer_id', $request->customer_id);
        if ($request->has('status')) $query->where('status', $request->status);
        if ($request->has('q')) $query->where('reference', 'like', '%' . $request->q . '%');

        $quotes = $query->orderBy('created_at', 'desc')->paginate(15);

        return SalesQuoteResource::collection($quotes);
    }

    public function show($id)
    {
        $quote = SalesQuote::with(['customer', 'items.product', 'creator'])->findOrFail($id);
        return new SalesQuoteResource($quote);
    }

    public function store(StoreSalesQuoteRequest $request)
    {
        $data = $request->validated();
        // $userId = auth()->id();

        DB::beginTransaction();
        try {
            $quote = SalesQuote::create([
                'reference' => $data['reference'] ?? 'Q-' . time(),
                'customer_id' => $data['customer_id'],
                'status' => $data['status'] ?? 'draft',
                'valid_until' => $data['valid_until'] ?? null,
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
                // 'created_by' => $userId,
                'meta' => $data['meta'] ?? null,
            ]);

            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            foreach ($data['items'] as $it) {
                $lineTotal = (float)$it['qty'] * (float)$it['unit_price'];
                $discount = isset($it['discount']) ? (float)$it['discount'] : 0;
                $taxAmount = isset($it['tax_amount']) ? (float)$it['tax_amount'] : 0;

                $subtotal += $lineTotal;
                $totalDiscount += $discount;
                $totalTax += $taxAmount;

                $item = SalesQuoteItem::create([
                    'quote_id' => $quote->id,
                    'product_id' => $it['product_id'],
                    'qty' => $it['qty'],
                    'unit_price' => $it['unit_price'],
                    'discount' => $discount,
                    'tax_amount' => $taxAmount,
                    'line_total' => $lineTotal - $discount + $taxAmount,
                    'meta' => $it['meta'] ?? null,
                ]);
            }

            $quote->update([
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'tax' => $totalTax,
                'total' => $subtotal - $totalDiscount + $totalTax,
            ]);

            DB::commit();

            return (new SalesQuoteResource($quote->load(['customer', 'items.product', 'creator'])))->response()->setStatusCode(201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create quote', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateSalesQuoteRequest $request, $id)
    {
        $quote = SalesQuote::with('items')->findOrFail($id);
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $quote->fill([
                'reference' => $data['reference'] ?? $quote->reference,
                'valid_until' => $data['valid_until'] ?? $quote->valid_until,
                'status' => $data['status'] ?? $quote->status,
                'meta' => $data['meta'] ?? $quote->meta,
            ]);
            $quote->save();

            if (isset($data['items'])) {
                // naive: delete existing and recreate. Could diff for efficiency.
                $quote->items()->delete();

                $subtotal = 0;
                $totalTax = 0;
                $totalDiscount = 0;
                foreach ($data['items'] as $it) {
                    $lineTotal = (float)$it['qty'] * (float)$it['unit_price'];
                    $discount = isset($it['discount']) ? (float)$it['discount'] : 0;
                    $taxAmount = isset($it['tax_amount']) ? (float)$it['tax_amount'] : 0;

                    $subtotal += $lineTotal;
                    $totalDiscount += $discount;
                    $totalTax += $taxAmount;

                    SalesQuoteItem::create([
                        'quote_id' => $quote->id,
                        'product_id' => $it['product_id'],
                        'qty' => $it['qty'],
                        'unit_price' => $it['unit_price'],
                        'discount' => $discount,
                        'tax_amount' => $taxAmount,
                        'line_total' => $lineTotal - $discount + $taxAmount,
                        'meta' => $it['meta'] ?? null,
                    ]);
                }

                $quote->update([
                    'subtotal' => $subtotal,
                    'discount' => $totalDiscount,
                    'tax' => $totalTax,
                    'total' => $subtotal - $totalDiscount + $totalTax,
                ]);
            }

            DB::commit();
            return new SalesQuoteResource($quote->load(['customer', 'items.product', 'creator']));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update quote', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $quote = SalesQuote::findOrFail($id);
        $quote->delete();
        return response()->json(['message' => 'Quote deleted']);
    }

    public function convert($id)
    {
        // Simple conversion: create SalesOrder with items and mark quote as converted.
        $quote = SalesQuote::with('items')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Create order (assuming SalesOrder model exists)
            // $order = \App\Models\SalesOrder::create([
            //     'reference' => 'SO-' . time(),
            //     'customer_id' => $quote->customer_id,
            //     'status' => 'pending',
            //     'subtotal' => $quote->subtotal,
            //     'discount' => $quote->discount,
            //     'tax' => $quote->tax,
            //     'total' => $quote->total,
            //     'created_by' => auth()->id(),
            // ]);

            // foreach ($quote->items as $it) {
            //     \App\Models\SalesOrderItem::create([
            //         'order_id' => $order->id,
            //         'product_id' => $it->product_id,
            //         'qty' => $it->qty,
            //         'unit_price' => $it->unit_price,
            //         'discount' => $it->discount,
            //         'tax_amount' => $it->tax_amount,
            //         'line_total' => $it->line_total,
            //     ]);
            // }

            $quote->update(['status' => 'converted']);
            DB::commit();

            // return response()->json(['message' => 'Quote converted', 'order_id' => $order->id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Conversion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
