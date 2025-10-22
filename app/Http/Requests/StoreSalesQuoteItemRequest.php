<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesQuoteItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'quote_id' => ['required', 'exists:sales_quotes,id'],
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['required', 'numeric', 'min:0.0001'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
