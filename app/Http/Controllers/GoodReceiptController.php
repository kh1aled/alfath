<?php

namespace App\Http\Controllers;

use App\Models\GoodReceipt;
use App\Models\GoodReceiptItems;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GoodReceiptController extends Controller
{
    /**
     * Display all goods receipts
     */
    public function index()
    {
        // Fetch all goods receipts with related purchase orders and items, ordered by latest
        $receipts = GoodReceipt::with(['purchaseOrder', 'items'])->latest()->get();
        return response()->json($receipts);
    }

    /**
     * Store a new goods receipt
     */
    public function store(Request $request)
    {

        // ✅ Validate the incoming request data
        $validated = $request->validate([
            'po_id' => 'required|exists:purchase_orders,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'receipt_date' => 'required|date',
            'status' => ['sometimes', 'string'],
            'invoice_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            // Validate each item
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.ordered_qty' => 'required|numeric|min:0',
            'items.*.received_qty' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // ✅ Check if Purchase Order status is open or partial
            $purchaseOrder = PurchaseOrder::findOrFail($validated['po_id']);
            if (!in_array($purchaseOrder->status, ['open', 'partial'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot create goods receipt for a purchase order that is not open or partial.',
                ], 400);
            }

            // ✅ Upload the invoice file if provided
            $invoicePath = null;
            if ($request->hasFile('invoice_image')) {
                $invoicePath = $request->file('invoice_image')->store('invoices', 'public');
            }

            // ✅ Create the main goods receipt record
            $receipt = GoodReceipt::create([
                'po_id' => $validated['po_id'],
                'supplier_id' => $validated['supplier_id'],
                'receipt_date' => $validated['receipt_date'],
                'received_by' => Auth::id(),
                'invoice_image' => $invoicePath,
                'status' => 'draft',
            ]);

            // ✅ Loop through all received items and store them
            foreach ($validated['items'] as $item) {
                $productId = $item['product_id'];
                $orderedQty = $item['ordered_qty'];
                $receivedQty = $item['received_qty'];

                // ❌ Check that received quantity does not exceed ordered
                if ($receivedQty > $orderedQty) {
                    throw new \Exception("Received quantity exceeds ordered quantity for product ID: {$productId}");
                }

                // ✅ Check total previously received for this product in same PO
                $previousReceived = DB::table('good_receipt_items')
                    ->join('good_receipts', 'good_receipt_items.good_receipt_id', '=', 'good_receipts.id')
                    ->where('good_receipts.po_id', $validated['po_id'])
                    ->where('good_receipt_items.item_id', $productId)
                    ->sum('good_receipt_items.received_qty');

                if ($previousReceived + $receivedQty > $orderedQty) {
                    throw new \Exception("Total received quantity exceeds ordered quantity for product ID: {$productId}");
                }

                // ✅ Save item under this receipt
                GoodReceiptItems::create([
                    'good_receipt_id' => $receipt->id,
                    'item_id' => $productId,
                    'ordered_qty' => $orderedQty,
                    'received_qty' => $receivedQty,
                ]);

                // ✅ Update product stock quantity
                Product::where('id', $productId)->increment('count', $receivedQty);
            }

            // ✅ Update the Purchase Order status (partial or fulfilled)
            $po = PurchaseOrder::with('items')->find($validated['po_id']);
            $totalOrdered = $po->items->sum('quantity');

            $totalReceived = DB::table('good_receipt_items')
                ->join('good_receipts', 'good_receipt_items.good_receipt_id', '=', 'good_receipts.id')
                ->where('good_receipts.po_id', $po->id)
                ->sum('good_receipt_items.received_qty');

            $poStatus = $totalReceived >= $totalOrdered ? 'fulfilled' : 'partial';
            $po->update(['status' => $poStatus]);

            // ✅ Determine the Goods Receipt status based on received quantities
            $totalOrderedItems = collect($validated['items'])->sum('ordered_qty');
            $totalReceivedItems = collect($validated['items'])->sum('received_qty');

            if ($totalReceivedItems == 0) {
                $receipt->update(['status' => 'draft']);
            } elseif ($totalReceivedItems < $totalOrderedItems) {
                $receipt->update(['status' => 'partial']);
            } else {
                $receipt->update(['status' => 'completed']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Goods Receipt created successfully.',
                'receipt' => $receipt,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a single goods receipt
     */
    public function show(GoodReceipt $GoodReceipt)
    {
        // Load the purchase order and item details for this receipt
        $GoodReceipt->load(['purchaseOrder', 'items.product']);
        return response()->json($GoodReceipt);
    }

    /**
     * Delete a goods receipt
     */
    public function destroy(GoodReceipt $goodsReceipt)
    {
        // Delete the invoice image from storage if it exists
        if ($goodsReceipt->invoice_image) {
            Storage::disk('public')->delete($goodsReceipt->invoice_image);
        }

        // Delete the goods receipt record
        $goodsReceipt->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Goods Receipt deleted successfully.'
        ]);
    }
}
