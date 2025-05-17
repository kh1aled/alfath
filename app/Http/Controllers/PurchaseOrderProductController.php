<?php

namespace App\Http\Controllers;

use App\Models\purchase_order_product;
use App\Http\Requests\Storepurchase_order_productRequest;
use App\Http\Requests\Updatepurchase_order_productRequest;

class PurchaseOrderProductController extends Controller
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
    public function store(Storepurchase_order_productRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(purchase_order_product $purchase_order_product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchase_order_product $purchase_order_product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatepurchase_order_productRequest $request, purchase_order_product $purchase_order_product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(purchase_order_product $purchase_order_product)
    {
        //
    }
}
