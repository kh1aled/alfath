<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'quote' => $this->whenLoaded('quote'),
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'order_date' => $this->order_date,
            'notes' => $this->notes,
            'items' => SalesOrderItemResource::collection($this->whenLoaded('items')),
            'created_by' => $this->creator?->name,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}
