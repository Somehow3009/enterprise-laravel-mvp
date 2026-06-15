<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'discount_amount' => ['sometimes', 'numeric', 'min:0'],
            'metadata' => ['sometimes', 'array'],
            'services' => ['required', 'array', 'min:1'],
            'services.*.id' => ['required', 'integer', 'exists:services,id'],
            'services.*.quantity' => ['required', 'integer', 'min:1', 'max:1000'],
            'services.*.unit_price' => ['sometimes', 'numeric', 'min:0'],
            'services.*.metadata' => ['sometimes', 'array'],
        ];
    }
}

