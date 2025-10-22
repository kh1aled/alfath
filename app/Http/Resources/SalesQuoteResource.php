<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesQuoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'customer' => [
                'id' => $this->customer->id ?? null,
                'name' => $this->customer->name ?? null,
            ],
            'status' => $this->status,
            'valid_until' => $this->valid_until ? $this->valid_until->toDateString() : null,
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'tax' => (float) $this->tax,
            'total' => (float) $this->total,
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => ['id' => $item->product->id ?? null, 'name' => $item->product->name ?? null, 'sku' => $item->product->sku ?? null],
                    'qty' => (float) $item->qty,
                    'unit_price' => (float) $item->unit_price,
                    'discount' => (float) $item->discount,
                    'tax_amount' => (float) $item->tax_amount,
                    'line_total' => (float) $item->line_total,
                ];
            }),
            'meta' => $this->meta,
            'created_at' => $this->created_at->toDateTimeString(),
            'created_by' => $this->creator ? ['id' => $this->creator->id, 'name' => $this->creator->name] : null,
        ];
    }
}
