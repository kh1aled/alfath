<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreTransferRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'authorized_by'      => ['nullable', 'integer', 'exists:users,id'],
            'created_by'         => ['required', 'integer', 'exists:users,id'],
            'date'               => ['required', 'date'],
            'from_warehouse'     => ['required', 'integer', 'exists:storages,id'],
            'to_warehouse_id'    => [
                'required',
                'integer',
                'exists:storages,id',
                // ensure it's different from from_warehouse
                Rule::notIn([$this->input('from_warehouse')]),
            ],
            'product'            => ['required', 'integer', 'exists:products,id'],
            'quantity'           => ['required', 'numeric', 'min:1'],
            'reason'             => ['nullable', 'string', 'max:1000'],
            'status'             => ['required', Rule::in(['pending', 'completed', 'cancelled'])],
            'transfer_note'      => ['nullable', 'string', 'max:2000'],

        ];
    }


    public function messages()
    {
        return [
            'authorized_by.integer' => 'Authorized by must be a valid user ID.',
            'authorized_by.exists'  => 'The authorized user does not exist.',
            'created_by.required'   => 'Creator is required.',
            'created_by.integer'    => 'Created by must be a valid user ID.',
            'created_by.exists'     => 'The creator user does not exist.',
            'date.required'         => 'Date is required.',
            'date.date'             => 'Date must be a valid date.',
            'from_warehouse.required' => 'From warehouse is required.',
            'from_warehouse.exists' => 'From warehouse does not exist.',
            'to_warehouse_id.required' => 'To warehouse is required.',
            'to_warehouse_id.exists' => 'To warehouse does not exist.',
            'to_warehouse_id.not_in' => 'From and To warehouse cannot be the same.',
            'product.required'      => 'Product is required.',
            'product.exists'        => 'Selected product does not exist.',
            'quantity.required'     => 'Quantity is required.',
            'quantity.numeric'      => 'Quantity must be a number.',
            'quantity.min'          => 'Quantity must be at least 1.',
            'status.required'       => 'Status is required.',
            'status.in'             => 'Invalid status selected.',
        ];
    }

    /**
     * Optional: prepare data before validation (e.g., cast string numbers to int)
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'authorized_by' => $this->input('authorized_by') !== null ? (int) $this->input('authorized_by') : null,
            'created_by' => $this->input('created_by') !== null ? (int) $this->input('created_by') : null,
            'from_warehouse' => $this->input('from_warehouse') !== null ? (int) $this->input('from_warehouse') : null,
            'to_warehouse_id' => $this->input('to_warehouse_id') !== null ? (int) $this->input('to_warehouse_id') : null,
            'product' => $this->input('product') !== null ? (int) $this->input('product') : null,
            'quantity' => $this->input('quantity') !== null ? (float) $this->input('quantity') : null,
        ]);
    }
}
