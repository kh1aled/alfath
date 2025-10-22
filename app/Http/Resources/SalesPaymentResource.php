<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesPaymentResource extends JsonResource
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
            'payment_number' => $this->payment_number,
            'invoice_id' => $this->invoice_id,
            'invoice_number' => $this->invoice->invoice_number ?? null,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer->name ?? null,
            'amount' => $this->amount,
            'payment_method' => $this->payment_method,
            'reference' => $this->reference,
            'payment_date' => $this->payment_date,
            'notes' => $this->notes,
            'created_by' => $this->createdBy->name ?? null,
            'updated_by' => $this->updatedBy->name ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
