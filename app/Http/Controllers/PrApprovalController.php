<?php

namespace App\Http\Controllers;

use App\Models\PrApproval;
use App\Http\Requests\StorePrApprovalRequest;
use App\Http\Requests\UpdatePrApprovalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PrApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $userId = Auth::id();

        $prApprovals = PrApproval::where("approver_id", $userId)->get();

        return response()->json(["user" => $userId, "approvals" => $prApprovals]);
    }



    public function indexWithPagination(Request $request)
    {
        $perPage = 10;
        $query = $request->query('query');
        $userId = Auth::id();

        $prApprovals = PrApproval::where("approver_id", $userId)
            ->orderBy('created_at', 'desc');

        if ($query) {
            $prApprovals->where(function ($q) use ($query) {
                $q->whereHas('purchaseRequisition', function ($subQ) use ($query) {
                    $subQ->where('name', 'like', "%$query%");
                })
                    ->orWhere('comments', 'like', "%$query%")
                    ->orWhere('status', 'like', "%$query%");
            });
        }

        $approvals = $prApprovals->with('requisition')->paginate($perPage);

        return response()->json([
            "data" => $approvals->items(),
            "links" => $approvals->linkCollection(),
            "from" => $approvals->firstItem(),
            "to" => $approvals->lastItem(),
            "total" => $approvals->total(),
        ]);
    }

    /**
     * Operations of approve and reject purchase request
     */
    public function approve(Request $request, $approvalId)
    {
        $approval = PrApproval::findOrFail($approvalId);

        // Update approval status
        $approval->status = 'approved'; // 'approved' or 'rejected'
        $approval->comments = $request->comment ?? null;
        $approval->approved_at = now();
        $approval->save();

        $requisition = $approval->requisition;

        //if purchase rejected -> all purchase rejected
        if ($request->status === 'rejected') {
            $requisition->status = 'rejected';
            $requisition->save();

            return response()->json(['message' => 'Requisition rejected.']);
        }


        //check if there approvals not approved
        $allApproved = $requisition->approvals()->where('status', '!=', 'approved');

        //if not exist any approvals not approved -> change status of request to approved
        if ($allApproved->count() === 0) {
            $requisition->status = 'approved';
            $requisition->save();

            // هنا ممكن تنشيء امر شراء
            // PurchaseOrder::create([
            //     'requisition_id' => $requisition->id,
            //     'created_by' => auth()->id(),
            //     'status' => 'draft',
            // ]);

            return response()->json(['message' => 'Requisition fully approved, PO created.']);
        }

        return response()->json(['message' => 'Approval submitted. Waiting for others.']);
    }




    public function reject(Request $request, $approvalId)
    {
        $approval = PrApproval::findOrFail($approvalId);

        // Update approval status
        $approval->status = 'rejected'; // 'approved' or 'rejected'
        $approval->comments = $request->comment ?? null;
        $approval->approved_at = now();
        $approval->save();


        $approval->requisition->update(['status' => 'rejected']);

        return response()->json(['message' => 'Rejected successfully']);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrApprovalRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PrApproval $prApproval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrApproval $prApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrApprovalRequest $request, PrApproval $prApproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrApproval $prApproval)
    {
        //
    }
}
