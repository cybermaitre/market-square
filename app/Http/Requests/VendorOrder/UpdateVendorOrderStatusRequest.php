<?php

namespace App\Http\Requests\VendorOrder;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\OrderStatus;
use Illuminate\Validation\Rule;

class UpdateVendorOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'status.enum' => 'The status must be one of: pending, processing, shipped, delivered.',
        ];
    }

}
