<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ownStoreId = $this->user()->store?->id;

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                'not_regex:/\//',
                Rule::unique('stores', 'name')->ignore($ownStoreId),
            ],
            'category' => ['sometimes', 'required', Rule::in(config('marketplace.store_categories'))],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Another store already uses this name.',
            'name.not_regex' => 'The store name cannot contain a slash (/).',
            'category.in' => 'Please choose a valid category.',
        ];
    }
}
