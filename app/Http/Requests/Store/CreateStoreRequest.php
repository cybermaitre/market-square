<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'not_regex:/\//', 'unique:stores,name'],
            'category' => ['required', Rule::in(config('marketplace.store_categories'))],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
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