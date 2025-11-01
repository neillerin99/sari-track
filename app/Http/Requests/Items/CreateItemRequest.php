<?php

namespace App\Http\Requests\Items;

use App\Http\Requests\BaseRequest;

class CreateItemRequest extends BaseRequest
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
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category_id' => 'uuid|exists:categories,id',
            'unit' => 'nullable|string|max:50',
            'barcode' => 'required|string|max:50|unique:items,barcode',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'store_id' => 'required|uuid',
        ];
    }
}
