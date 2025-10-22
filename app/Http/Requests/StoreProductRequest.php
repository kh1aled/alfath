<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            //
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'nullable|integer',
            'count' => 'nullable|integer',
            'minimum_quantity' => 'nullable|integer',
            'unit' => 'nullable|string|max:255',
            'buying_price' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'status' => 'nullable|string|in:active,inactive',
        ];
    }
}
