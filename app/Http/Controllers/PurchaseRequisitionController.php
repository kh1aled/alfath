<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequisition;
use App\Http\Requests\StorePurchaseRequisitionRequest;
use App\Http\Requests\UpdatePurchaseRequisitionRequest;
use App\Models\ApprovalMatrix;
use App\Models\PurchaseRequisitionItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class PurchaseRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $purchasesReq = PurchaseRequisition::with("items", 'approvals', 'requester', 'attachments', 'approver')->get();


        return response()->json($purchasesReq);
    }


    public function indexWithPagination(Request $request)
    {
        $perPage = 10;
        $query = $request->query('query');

        $purchasesReq = PurchaseRequisition::with("items", 'approvals', 'requester', 'attachments', 'approver');

        if ($query) {
            $purchasesReq->where(function ($q) use ($query) {
                $q->whereHas('requester', function ($subQ) use ($query) {
                    $subQ->where('name', 'like', "%$query%");
                })
                    ->orWhere('priority', 'like', "%$query%")
                    ->orWhere('status', 'like', "%$query%");
            });
        }

        $purchases = $purchasesReq->paginate($perPage);



        return response()->json($purchases);
    }

    public function indexApproved(Request $request)
    {
    
        $purchasesReq = PurchaseRequisition::where('status' , 'approved')->get();

        return response()->json($purchasesReq);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequisitionRequest $request)
    {

        // Validate Data
        $validatedData = $request->validated();

        $items = $validatedData['items'];


        // Generate Unique PR Code
        do {
            $code = 'PR-' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (PurchaseRequisition::where('code', $code)->exists());

        // Create Purchase Requisition
        $purchaseReq = PurchaseRequisition::create([
            'code'           => $code,
            'requester_id'   => $validatedData['requester_id'],
            'priority'       => $validatedData['priority'],
            'status'         => "pending",
            'needed_by_date' => $validatedData['needed_by_date'],
            'purpose'        => $validatedData['purpose'],
            'notes'          => $validatedData['notes'],
            'created_by' => Auth::id(),
        ]);

        // Create Purchase Requisition Items
        if ($items) {

            foreach ($items as $item) {
                // Generate unique item code
                do {
                    $item_code = 'ITEM-' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                } while (PurchaseRequisitionItems::where('item_code', $item_code)->exists());

                $purchaseReq->items()->create([
                    'item_code'       => $item_code,
                    'quantity'        => $item['quantity'],
                    'unit'            => $item['unit'],
                    'estimated_price' => $item['estimated_price'],
                    'total_estimated' => $item['estimated_price'] * $item['quantity'],
                    'notes'           => $item['notes'] ?? null,
                    'status'          => 'pending',
                ]);
            }
        }

        // Calculate total price
        $total = collect($request->items ?? [])->sum('estimated_price');

        // Get approvals based on total amount
        $approvals = ApprovalMatrix::where('min_amount', '<=', $total)
            ->where('max_amount', '>=', $total)
            ->get();

        foreach ($approvals as $approval) {
            $purchaseReq->approvals()->create([
                "approver_id" => $approval->approver_id,
                "status"      => 'pending',
                'comments'    => NULL,
                'approved_at' => NULL,
            ]);
        }

        //Save Files in Server
        //Get the uploaded file

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('purchase_requests_attachments', 'public');

                $purchaseReq->attachments()->create([
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'uploaded_by' => Auth::id(),
                    'uploaded_at' => now(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Purchase Requisition Created Successfully',
            'data'    => $purchaseReq->load('items', 'approvals')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchaseReq = PurchaseRequisition::with("items", 'approvals', 'requester', 'attachments', 'approver')
            ->where("id", $id)
            ->first();

        if (!$purchaseReq) {
            return response()->json(["message" => "Purchase Requisition not found"], 404);
        }

        return response()->json($purchaseReq);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequisitionRequest $request, PurchaseRequisition $purchaseRequisition)
    {

        $validatedData = $request->validated();
        $items = $validatedData['items'];

        // Update Main Purchase Requisition
        $purchaseRequisition->update([
            'requester_id'   => $validatedData['requester_id'],
            'priority'       => $validatedData['priority'],
            'needed_by_date' => $validatedData['needed_by_date'],
            'purpose'        => $validatedData['purpose'],
            'notes'          => $validatedData['notes'],
            'status'          => 'pending',
            'created_by' => Auth::id(),
        ]);

        // Remove old items and recreate them
        $purchaseRequisition->items()->delete();

        if ($items) {
            foreach ($items as $item) {
                do {
                    $item_code = 'ITEM-' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                } while (PurchaseRequisitionItems::where('item_code', $item_code)->exists());

                $purchaseRequisition->items()->create([
                    'item_code'       => $item_code,
                    'quantity'        => $item['quantity'],
                    'unit'            => $item['unit'],
                    'estimated_price' => $item['estimated_price'],
                    'total_estimated' => $item['estimated_price'] * $item['quantity'],
                    'notes'           => $item['notes'] ?? null,
                    'status'          => 'pending',
                ]);
            }
        }

        // Attachments

        //1-delete attachments that removed from frontend
        if ($request->has('deleted_attachments') && is_array($request->deleted_attachments)) {
            $deletedAttachments = $request->deleted_attachments;

            //delete files from server and database
            foreach ($purchaseRequisition->attachments()->whereIn('id', $deletedAttachments)->get() as $att) {
                if (Storage::disk('public')->exists($att->file_path)) {
                    Storage::disk('public')->delete($att->file_path);
                }
                $att->delete();
            }
        }

        //add new files
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('purchase_requests_attachments', 'public');

                $purchaseRequisition->attachments()->create([
                    'file_path'   => $path,
                    'file_type'   => $file->getClientOriginalExtension(),
                    'uploaded_by' => Auth::id(),
                    'uploaded_at' => now(),
                ]);
            }
        }


        return response()->json([
            'message' => 'Purchase Requisition Updated Successfully',
            'data'    => $purchaseRequisition->load('items', 'approvals', 'attachments')
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseRequisition $purchaseRequisition)
    {
        //
        foreach ($purchaseRequisition->attachments() as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }

        $purchaseRequisition->items()->delete();
        $purchaseRequisition->approvals()->delete();
        $purchaseRequisition->attachments()->delete();

        $purchaseRequisition->delete();

        return response()->json([
            'message' => 'Purchase Requisition Deleted Successfully'
        ], 200);
    }

    public function exportPdf(Request $request)
    {
        $PR = PurchaseRequisition::with('requester')->get();

        $pdf = PDF::loadView('purchases.pr.pdf', [
            'PR' => $PR
        ]);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="PurchaseRequisitions-' . time() . '.pdf"',
        ]);
    }



}
