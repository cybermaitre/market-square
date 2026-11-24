<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Admin is deliberately not allowed here
            'role' => ['required', Rule::in([UserRole::Buyer->value, UserRole::Vendor->value])],
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'Role must be either buyer or vendor.',
        ];
    }
}