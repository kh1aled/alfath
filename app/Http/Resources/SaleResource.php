<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'sale_number' => $this->sale_number,
            'customer' => $this->customer?->name,
            'warehouse' => $this->warehouse?->name,
            'total_amount' => $this->total_amount,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'sale_date' => $this->sale_date,
            'items' => SaleItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
