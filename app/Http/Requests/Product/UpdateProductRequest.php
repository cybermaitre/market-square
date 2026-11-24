<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'unit_available' => ['sometimes', 'required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'price.min' => 'The price must be greater than zero.',
            'unit_available.min' => 'The available units cannot be negative.',
        ];
    }
}
