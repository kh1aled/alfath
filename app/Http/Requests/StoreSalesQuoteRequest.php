<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // return auth()->check(); // adjust per permissions
        return true;
    }

    public function rules()
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'reference' => ['nullable', 'string', 'max:191'],
            'valid_until' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'numeric', 'min:0.0001'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.tax_amount' => ['nullable', 'numeric', 'min:0'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
