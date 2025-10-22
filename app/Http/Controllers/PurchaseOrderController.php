<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\ApprovalMatrix;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display all purchase orders.
     */
    public function index()
    {
        $purchasesOrders = PurchaseOrder::with(['items', 'supplier'])->get();

        return response()->json([
            'data' => $purchasesOrders
        ]);
    }

    /**
     * Display paginated purchase orders with search.
     */
    public function indexWithPagination(Request $request)
    {
        $perPage = 10;
        $query = $request->query('query');

        $purchasesOrders = PurchaseOrder::with(['items', 'supplier']);

        if ($query) {
            $purchasesOrders->where(function ($q) use ($query) {
                $q->whereHas('supplier', function ($subQ) use ($query) {
                    $subQ->where('name', 'like', "%$query%");
                })
                    ->orWhere('po_number', 'like', "%$query%")
                    ->orWhere('status', 'like', "%$query%");
            });
        }

        return response()->json($purchasesOrders->paginate($perPage));
    }

    /**
     * Store a newly created purchase order.
     */
    public function store(StorePurchaseOrderRequest $request)
    {
        $validated = $request->validated();

        $newOrder = DB::transaction(function () use ($validated) {

            // ✅ Create new purchase order
            $newOrder = PurchaseOrder::create([
                'pr_id'         => $validated['pr_id'],
                'supplier_id'   => $validated['supplier_id'],
                'order_date'    => $validated['order_date'],
                'status'        => 'draft',
                'currency'      => $validated['currency'],
                'payment_terms' => $validated['payment_terms'],
                'tax'           => $validated['tax'],
                'discount'      => $validated['discount'],
                'total_amount'  => $validated['total_amount'],
                'created_by'    => Auth::id(),
                'approved_by'   => null,
            ]);

            // ✅ Handle each item
            foreach ($validated['items'] as $item) {
                $productId = $this->findOrCreateProduct($item);

                $newOrder->items()->create([
                    'product_id'  => $productId,
                    'name'        => trim($item['name']),
                    'description' => trim($item['description'] ?? ''),
                    'quantity'    => $item['quantity'],
                    'unit'        => $item['unit'],
                    'unit_price'  => $item['unit_price'],
                    'line_total'  => $item['quantity'] * $item['unit_price'],
                    'notes'       => $item['notes'] ?? null,
                ]);
            }

            // ✅ Create approvals
            $this->createApprovals($newOrder, $validated['total_amount']);

            return $newOrder;
        });

        return response()->json([
            'message' => 'Purchase order created successfully',
            'data'    => $newOrder->load('items', 'approvals')
        ], 201);
    }

    /**
     * Display a specific purchase order.
     */
    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with(['items', 'supplier'])
            ->find($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Purchase order not found'], 404);
        }

        return response()->json($purchaseOrder);
    }

    /**
     * Update a purchase order.
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $PurchaseOrder)
    {

        $validated = $request->validated();

        // ✅ Decode items if coming as JSON string
        if (is_string($validated['items'])) {
            $validated['items'] = json_decode($validated['items'], true);
        }

        $updatedOrder = DB::transaction(function () use ($validated, $PurchaseOrder) {

            // ✅ Update main purchase order fields
            $PurchaseOrder->update([
                'pr_id'         => $validated['pr_id'],
                'supplier_id'   => $validated['supplier_id'],
                'order_date'    => $validated['order_date'],
                'status'        => $validated['status'] ?? $PurchaseOrder->status,
                'currency'      => $validated['currency'],
                'payment_terms' => $validated['payment_terms'],
                'tax'           => $validated['tax'],
                'discount'      => $validated['discount'],
                'total_amount'  => $validated['total_amount'],
                'approved_by'   => $validated['approved_by'] ?? $PurchaseOrder->approved_by,
            ]);

            // ✅ Delete old items before re-creating them
            $PurchaseOrder->items()->delete();


            $PurchaseOrder = PurchaseOrder::findOrFail($PurchaseOrder->id);


            foreach ($validated['items'] as $item) {
                $productId = $this->findOrCreateProduct($item);

                $PurchaseOrder->items()->create([
                    'product_id'  => $productId,
                    'name'        => trim($item['name']),
                    'description' => trim($item['description'] ?? ''),
                    'quantity'    => (float)$item['quantity'],
                    'unit'        => $item['unit'],
                    'unit_price'  => (float)$item['unit_price'],
                    'line_total'  => (float)$item['quantity'] * (float)$item['unit_price'],
                    'notes'       => $item['notes'] ?? null,
                ]);
            }

            // ✅ Reset approvals
            $PurchaseOrder->approvals()->delete();
            $this->createApprovals($PurchaseOrder, $validated['total_amount']);

            return $PurchaseOrder;
        });

        return response()->json([
            'message' => 'Purchase order updated successfully',
            'data'    => $updatedOrder->load('items', 'approvals'),
        ], 200);
    }


    /**
     * Delete a purchase order and related data.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        DB::transaction(function () use ($purchaseOrder) {
            $purchaseOrder->items()->delete();
            $purchaseOrder->approvals()->delete();
            $purchaseOrder->delete();
        });

        return response()->json(['message' => 'Purchase order and related data deleted successfully.']);
    }

    /**
     * Approve a purchase order.
     */
    public function approve($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if ($purchaseOrder->status !== 'draft') {
            return response()->json(['message' => 'This order cannot be approved.'], 400);
        }

        $purchaseOrder->update([
            'status' => 'open',
            'approved_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Purchase order approved successfully.']);
    }

    /**
     * 🔧 Helper: Find or create product
     */
    private function findOrCreateProduct($item)
    {
        $name = trim($item['name']);
        $description = trim($item['description'] ?? '');

        $product = Product::where('name', 'LIKE', $name)->first();

        if ($product) {
            $product->increment('count', $item['quantity']);
            return $product->id;
        }

        $newProduct = Product::create([
            'name'             => $name,
            'description'      => $description,
            'category_id'      => null,
            'count'            => $item['quantity'],
            'minimum_quantity' => 1,
            'unit'             => $item['unit'],
            'buying_price'     => $item['unit_price'],
            'selling_price'    => $item['unit_price'],
            'weight'           => null,
            'image'            => null,
            'status'           => 'interactive',
        ]);

        return $newProduct->id;
    }

    /**
     * 🔧 Helper: Create approvals for purchase order
     */
    private function createApprovals(PurchaseOrder $order, $total)
    {
        $approvers = ApprovalMatrix::where('min_amount', '<=', $total)
            ->where(function ($q) use ($total) {
                $q->where('max_amount', '>=', $total)
                    ->orWhereNull('max_amount');
            })
            ->orderBy('level')
            ->get();

        foreach ($approvers as $approval) {
            $order->approvals()->create([
                'approver_id' => $approval->approver_id,
                'status'      => 'pending',
                'comments'    => null,
                'approved_at' => null,
            ]);
        }
    }
}
