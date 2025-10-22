<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesQuoteItemResource extends JsonResource
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
            'product' => [
                'id' => $this->product->id ?? null,
                'name' => $this->product->name ?? null,
                'sku' => $this->product->sku ?? null,
            ],
            'qty' => (float)$this->qty,
            'unit_price' => (float)$this->unit_price,
            'discount' => (float)$this->discount,
            'tax_amount' => (float)$this->tax_amount,
            'line_total' => (float)$this->line_total,
            'meta' => $this->meta,
        ];
    }
}
