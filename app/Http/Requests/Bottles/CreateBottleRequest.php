<?php

namespace App\Http\Requests\Bottles;

use Illuminate\Foundation\Http\FormRequest;

class CreateBottleRequest extends FormRequest
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
            'store_id' => 'required|uuid',
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ];
    }
}
