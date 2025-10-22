<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderItems;
use App\Http\Requests\StorePurchaseOrderItemsRequest;
use App\Http\Requests\UpdatePurchaseOrderItemsRequest;

class PurchaseOrderItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorePurchaseOrderItemsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrderItems $purchaseOrderItems)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrderItems $purchaseOrderItems)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseOrderItemsRequest $request, PurchaseOrderItems $purchaseOrderItems)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrderItems $purchaseOrderItems)
    {
        //
    }
}
