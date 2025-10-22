<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('items') && is_string($this->items)) {
            $this->merge([
                'items' => json_decode($this->items, true),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pr_id' => [
                'required',
                'integer',
                Rule::exists('purchase_requisitions', 'id')->where('status', 'approved'),
            ],
            'supplier_id'    => 'required|integer|exists:suppliers,id',
            'order_date'     => 'required|date',
            'status' => ['required','string', Rule::in(values: ['draft', 'open', 'fulfilled', 'cancelled' , 'partial'])],
            'currency'       => 'required|string|max:10',
            'payment_terms'  => 'required|string|max:50',
            'tax'            => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'total_amount'   => 'required|numeric|min:0',
            'created_by'     => 'required|integer|exists:users,id',
            'approved_by'    => 'nullable|integer|exists:users,id',

            // items validation
            'items'                          => 'required|array|min:1',
            'items.*.description'            => 'required|string|max:255',
            'items.*.name'                   =>        'required|string|max:155',
            'items.*.quantity'               => 'required|numeric|min:1',
            'items.*.unit'                   => 'required|string|max:50',
            'items.*.unit_price'             => 'required|numeric|min:0',
            'items.*.line_total'             => 'required|numeric|min:0',
            'items.*.notes'                  => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'pr_id.required' => 'The purchase request ID is required.',
            'pr_id.integer' => 'The purchase request ID must be an integer.',
            'pr_id.exists' => 'The selected purchase request must be approved before creating a purchase order.',
            'supplier_id.required' => 'The supplier ID is required.',
            'supplier_id.integer' => 'The supplier ID must be an integer.',
            'supplier_id.exists' => 'The selected supplier does not exist.',

            'order_date.required' => 'The order date is required.',
            'order_date.date' => 'The order date must be a valid date.',

            'status.required' => 'The order status is required.',
            // 'status.in' => 'The order status must be one of: draft, pending, approved, or rejected.',

            'currency.required' => 'The currency is required.',
            'currency.max' => 'The currency code may not exceed 10 characters.',

            'payment_terms.required' => 'Payment terms are required.',

            'tax.required' => 'Tax value is required.',
            'tax.numeric' => 'Tax must be a number.',
            'tax.min' => 'Tax value cannot be negative.',

            'discount.numeric' => 'Discount must be a number.',
            'discount.min' => 'Discount value cannot be negative.',

            'total_amount.required' => 'Total amount is required.',
            'total_amount.numeric' => 'Total amount must be a number.',

            'created_by.required' => 'The creator user ID is required.',
            'created_by.exists' => 'The specified creator user does not exist.',

            'items.required' => 'At least one item is required.',
            'items.array' => 'Items must be provided as an array.',

            'items.*.description.required' => 'Each item must have a description.',
            'items.*.name.required' => 'Each item must have a name.',
            'items.*.quantity.required' => 'Each item must have a quantity.',
            'items.*.quantity.numeric' => 'The item quantity must be a number.',
            'items.*.unit.required' => 'Each item must have a unit.',
            'items.*.unit_price.required' => 'Each item must have a unit price.',
            'items.*.unit_price.numeric' => 'The unit price must be a number.',
            'items.*.line_total.required' => 'Each item must have a line total.',
            'items.*.line_total.numeric' => 'The line total must be a number.',
            'items.*.notes.string' => 'Notes must be text.',
        ];
    }
}
