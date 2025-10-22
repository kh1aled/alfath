<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesReturnResource extends JsonResource
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
            'return_number' => $this->return_number,
            'invoice_id' => $this->invoice_id,
            'invoice_number' => $this->invoice->invoice_number ?? null,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer->name ?? null,
            'return_date' => $this->return_date,
            'total_amount' => $this->total_amount,
            'status' => $this->status,
            'notes' => $this->notes,
            'items' => SalesReturnItemResource::collection($this->whenLoaded('items')),
            'created_by' => $this->createdBy->name ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
