<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCalculateRequest extends FormRequest
{
    /**
     * Authorization is not part of the scope
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'sometimes|integer',
            'customer-id' => 'required|integer',
            'items' => 'required|array',
            'items.*.product-id' => 'required|string',
            'items.*.quantity' => 'required|integer',
            'items.*.unit-price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
            'total' => 'required|numeric',
        ];
    }
}
