<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequisitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


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
            //requester_id , priority, status , needed_by_date , purpose , notes , items [{"quantity":"","unit":"","estimated_price":"","notes":""}] , approvals [{"status":"pending","comments":"","approved_at":""}] , attachments[] (binary)

            //main requist
            'requester_id'   => 'required|integer|exists:users,id',
            'priority'       => 'required|string|in:low,normal,high',
            'needed_by_date' => 'required|date|after_or_equal:today',
            'purpose'        => 'required|string|max:255',
            'notes'          => 'nullable|string|max:500',

            //items
            'items'                   => 'required|array|min:1',
            'items.*.quantity'        => 'required|numeric|min:1',
            'items.*.unit'            => 'required|string|max:50',
            'items.*.estimated_price' => 'required|numeric|min:0',
            'items.*.notes'           => 'nullable|string|max:255',

            //Approvals 
            // 'approvals'                    => 'nullable|array',
            // 'approvals.*.status'           => 'required|string|in:pending,approved,rejected',
            // 'approvals.*.comments'         => 'nullable|string|max:255',
            // 'approvals.*.approved_at'      => 'nullable|date',

            //attachments
            'attachments'                  => 'nullable|array',
            'attachments.*'                => 'file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',

        ];
    }

    public function messages(): array
    {
        return [
            // Items array
            'items.required' => 'You must add at least one item to the requisition.',
            'items.array'    => 'Items must be a valid array.',
            'items.min'      => 'The requisition must contain at least one item.',

            // Each item validation
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.numeric'  => 'Quantity must be a number.',
            'items.*.quantity.min'      => 'Quantity must be at least 1.',

            'items.*.unit.required' => 'Unit is required for each item.',
            'items.*.unit.string'   => 'Unit must be a text value.',
            'items.*.unit.max'      => 'Unit must not exceed 50 characters.',

            'items.*.estimated_price.required' => 'Estimated price is required.',
            'items.*.estimated_price.numeric'  => 'Estimated price must be a number.',
            'items.*.estimated_price.min'      => 'Estimated price cannot be less than 0.',

            'items.*.notes.string' => 'Notes must be a text value.',
            'items.*.notes.max'    => 'Notes must not exceed 255 characters.',
        ];
    }
}
