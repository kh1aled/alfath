<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product' => $this->product->name ?? null,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'subtotal' => $this->subtotal,
        ];
    }
}
