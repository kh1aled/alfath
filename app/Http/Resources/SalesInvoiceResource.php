<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesInvoiceResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'order' => $this->whenLoaded('order'),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'invoice_date' => $this->invoice_date,
            'due_date' => $this->due_date,
            'notes' => $this->notes,
            'items' => SalesInvoiceItemResource::collection($this->whenLoaded('items')),
            'created_by' => $this->creator?->name,
        ];
    
    }
}
