<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferRequest;
use App\Models\ProductStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TransferController extends Controller
{
    /**
     * List all transfers with relations.
     */
    public function index(): JsonResponse
    {
        $transfers = Transfer::with([
            'fromStorage',
            'toStorage',
            'creator',
            'authorizer',
            'product'
        ])->get();

        return response()->json($transfers);
    }

    /**
     * Store a newly created transfer.
     */
    public function store(StoreTransferRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $reference = 'S-' . now()->format('ymdHis') . rand(100, 999);

        try {
            $transfer = null;

            if ($validated['status'] === 'completed') {
                $transfer = DB::transaction(function () use ($validated, $reference) {
                    // Lock source for concurrency
                    $fromStorageRecord = ProductStorage::where('product_id', $validated['product'])
                        ->where('storage_id', $validated['from_warehouse'])
                        ->lockForUpdate()
                        ->first();

                    if (!$fromStorageRecord) {
                        throw ValidationException::withMessages([
                            'product' => ['Product does not exist in source warehouse.'],
                        ]);
                    }

                    if ($fromStorageRecord->quantity < $validated['quantity']) {
                        throw ValidationException::withMessages([
                            'quantity' => ['Insufficient quantity in source warehouse.'],
                        ]);
                    }

                    // Deduct from source
                    $fromStorageRecord->quantity -= $validated['quantity'];
                    $fromStorageRecord->save();

                    // Add to destination (create if missing)
                    $toStorageRecord = ProductStorage::firstOrCreate(
                        [
                            'product_id' => $validated['product'],
                            'storage_id' => $validated['to_warehouse_id'],
                        ],
                        ['quantity' => 0]
                    );
                    $toStorageRecord->quantity += $validated['quantity'];
                    $toStorageRecord->save();

                    // Create transfer record
                    return Transfer::create([
                        'reference'         => $reference,
                        'authorized_by'     => $validated['authorized_by'] ?? null,
                        'created_by'        => $validated['created_by'],
                        'date'              => $validated['date'],
                        'from_storage_id'   => $validated['from_warehouse'],
                        'to_storage_id'     => $validated['to_warehouse_id'],
                        'product_id'        => $validated['product'],
                        'quantity'          => $validated['quantity'],
                        'reason'            => $validated['reason'] ?? null,
                        'status'            => $validated['status'],
                        'note'              => $validated['transfer_note'] ?? null,
                    ]);
                });
            } else {
                $transfer = Transfer::create([
                    'reference'         => $reference,
                    'authorized_by'     => $validated['authorized_by'] ?? null,
                    'created_by'        => $validated['created_by'],
                    'date'              => $validated['date'],
                    'from_storage_id'   => $validated['from_warehouse'],
                    'to_storage_id'     => $validated['to_warehouse_id'],
                    'product_id'        => $validated['product'],
                    'quantity'          => $validated['quantity'],
                    'reason'            => $validated['reason'] ?? null,
                    'status'            => $validated['status'],
                    'note'              => $validated['transfer_note'] ?? null,
                ]);
            }

            return response()->json([
                'message' => 'Transfer created successfully.',
                'data' => $transfer,
            ], 201);
        } catch (ValidationException $ve) {
            return response()->json([
                'message' => $ve->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Transfer store error: ' . $e->getMessage(), ['trace' => $e->getTrace()]);
            if (config('app.debug')) {
                return response()->json([
                    'message' => 'Error creating transfer.',
                    'error' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ], 500);
            }
            return response()->json([
                'message' => 'Error creating transfer.',
            ], 500);
        }
    }


    /**
     * Show a single transfer.
     */
    public function show(Transfer $transfer): JsonResponse
    {
        $transfer->load(['fromStorage', 'toStorage', 'creator', 'authorizer', 'product']);

        return response()->json($transfer);
    }

    /**
     * Update allowed fields on a transfer.
     * (Not adjusting quantities here to avoid complex reconciliation logic.)
     */
    public function update(UpdateTransferRequest $request, Transfer $transfer): JsonResponse
    {
        $validated = $request->validated();

        try {
            // Only allow updating some fields; avoid changing source/destination/product/quantity without business logic
            $updatable = [
                'authorized_by' => $validated['authorized_by'] ?? $transfer->authorized_by,
                'status' => $validated['status'] ?? $transfer->status,
                'reason' => $validated['reason'] ?? $transfer->reason,
                'note' => $validated['transfer_note'] ?? $transfer->note,
            ];

            $transfer->update($updatable);

            return response()->json([
                'message' =>'Transfer updated successfully.',
                'data' => $transfer->fresh(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Transfer update error: ' . $e->getMessage(), ['trace' => $e->getTrace()]);
            return response()->json([
                'message' => 'Error updating transfer.',
            ], 500);
        }
    }

    /**
     * Delete a transfer.
     */
    public function destroy(Transfer $transfer): JsonResponse
    {
        try {
            $transfer->delete();
            return response()->json([
                'message' =>'Transfer deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Transfer delete error: ' . $e->getMessage(), ['trace' => $e->getTrace()]);
            return response()->json([
                'message' => 'Failed to delete transfer.',
            ], 500);
        }
    }
}
