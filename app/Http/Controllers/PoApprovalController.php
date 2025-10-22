<?php

namespace App\Http\Controllers;

use App\Models\PoApproval;
use App\Http\Requests\StorePoApprovalRequest;
use App\Http\Requests\UpdatePoApprovalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $userId = Auth::id();

        $prApprovals = PoApproval::where("approver_id", $userId)->get();

        return response()->json(["user" => $userId, "approvals" => $prApprovals]);
    }



    public function indexWithPagination(Request $request)
    {
        $perPage = 10;
        $query = $request->query('query');
        $userId = Auth::id();

        $poApprovals = PoApproval::where("approver_id", $userId)->with('order')
            ->orderBy('created_at', 'desc');

        if ($query) {
            $poApprovals->where(function ($q) use ($query) {
                $q->whereHas('purchaseRequisition', function ($subQ) use ($query) {
                    $subQ->where('name', 'like', "%$query%");
                })
                    ->orWhere('comments', 'like', "%$query%")
                    ->orWhere('status', 'like', "%$query%");
            });
        }

        $approvals = $poApprovals->with('order', 'order.requisition')->paginate($perPage);

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
        $approval = PoApproval::findOrFail($approvalId);

        DB::transaction(function () use ($approval, $request) {
            $approval->status = 'approved'; //['approved', 'rejected', 'pending']
            $approval->comments = $request->comment ?? null;
            $approval->approved_at = now();
            $approval->save();

            $order = $approval->order;

            $pendingApprovals = PoApproval::where('po_id', $order->id)
                ->where('status', 'pending')
                ->count();

            if ($pendingApprovals === 0) {
                $order->status = 'fulfilled';
                $order->save();
            }
        });

        return response()->json(['message' => 'Approval processed successfully.']);
    }



    public function reject(Request $request, $approvalId)
    {
        $approval = PoApproval::findOrFail($approvalId);

        DB::transaction(function () use ($approval, $request) {
            $approval->status = 'rejected'; // ['approved', 'rejected', 'pending']
            $approval->comments = $request->comment ?? null;
            $approval->approved_at = now();
            $approval->save();

            $order = $approval->order;

            $order->status = 'cancelled';
            $order->save();
        });

        return response()->json(['message' => 'Purchase order rejected successfully.']);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePoApprovalRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PoApproval $poApproval)
    {
        $approval = $poApproval->with('order', 'order.requisition', 'order.requisition.requester')->get();

        return response()->json($approval);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PoApproval $poApproval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoApprovalRequest $request, PoApproval $poApproval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PoApproval $poApproval)
    {
        //
    }
}
