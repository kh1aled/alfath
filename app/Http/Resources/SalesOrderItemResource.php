<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product->name ?? null,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,
        ];
    }
}
