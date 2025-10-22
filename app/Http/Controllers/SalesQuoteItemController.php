<?php

namespace App\Http\Controllers;

use App\Http\Resources\SalesQuoteItemResource;
use App\Models\SalesQuoteItem;
use App\Http\Requests\StoreSalesQuoteItemRequest;

class SalesQuoteItemController extends Controller
{
    public function store(StoreSalesQuoteItemRequest $request)
    {
        $item = SalesQuoteItem::create($request->validated());
        return new SalesQuoteItemResource($item->load(['product', 'quote']));
    }

    public function update(StoreSalesQuoteItemRequest $request, $id)
    {
        $item = SalesQuoteItem::findOrFail($id);
        $item->update($request->validated());
        return new SalesQuoteItemResource($item->load(['product', 'quote']));
    }

    public function destroy($id)
    {
        SalesQuoteItem::findOrFail($id)->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}


