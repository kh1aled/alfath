<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
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
            'product' => $this->product->name ?? null,
            'storage' => $this->storage->name ?? null,
            'quantity' => $this->quantity,
            'movement_type' => $this->movement_type,
            'reference_id' => $this->reference_id,
            'reference_type' => $this->reference_type,
            'notes' => $this->notes,
            'created_by' => $this->creator->name ?? null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
