<?php

namespace App\Http\Requests\Stores;

use App\Http\Requests\BaseRequest;


class CreateStoreRequest extends BaseRequest
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
            'baranggay' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'province' => 'required|string|max:50',
            'profile' => 'nullable|string|max:255',
        ];
    }

}
