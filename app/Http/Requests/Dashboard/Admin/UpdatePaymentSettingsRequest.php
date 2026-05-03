<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'stripe_publishable_key' => ['nullable', 'string', 'max:255'],
            'stripe_secret_key' => ['nullable', 'string', 'max:255'],
            'stripe_webhook_secret' => ['nullable', 'string', 'max:255'],
            'stripe_mode' => ['required', Rule::in(['test', 'live'])],
            'stripe_enabled' => ['nullable', 'boolean'],
            'reservation_currency' => ['required', 'string', 'size:3'],
            'reservation_deposit_percentage' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}
