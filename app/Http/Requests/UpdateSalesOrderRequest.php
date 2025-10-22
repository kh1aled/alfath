<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalesOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes|exists:customers,id',
            'quote_id' => 'nullable|exists:sales_quotes,id',
            'order_date' => 'nullable|date',
            'status' => 'nullable|string|in:pending,confirmed,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
            'items' => 'sometimes|array|min:1',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
        ];
    }
}
