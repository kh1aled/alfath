<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesInvoiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name ?? null,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'discount' => $this->discount,
            'tax_rate' => $this->tax_rate,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
