<?php

namespace App\Http\Requests\Batches;

use Illuminate\Foundation\Http\FormRequest;

class CreateBatchRequest extends FormRequest
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
            'item_id' => 'required|uuid',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|max:255',
        ];
    }
}
