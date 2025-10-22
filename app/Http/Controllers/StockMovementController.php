<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockMovementResource;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with(['product', 'warehouse', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return StockMovementResource::collection($movements);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'storage_id' => 'required|exists:storages,id',
            'quantity' => 'required|numeric',
            'movement_type' => 'required|in:purchase,sale,return,transfer_in,transfer_out,adjustment',
            'reference_id' => 'nullable|integer',
            'reference_type' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data['created_by'] = Auth::id();

        $movement = StockMovement::create($data);

        return new StockMovementResource($movement);
    }

    public function show(StockMovement $stockMovement)
    {
        return new StockMovementResource($stockMovement->load(['product', 'warehouse', 'creator']));
    }

    public function destroy(StockMovement $stockMovement)
    {
        $stockMovement->delete();
        return response()->json(['message' => 'Stock movement deleted successfully.']);
    }
}
