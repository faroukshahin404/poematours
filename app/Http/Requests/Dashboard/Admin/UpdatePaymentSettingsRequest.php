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
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        return [
            'enabled' => ['required', 'boolean'],
            'publishable_key' => ['nullable', 'string', 'max:255'],
            'secret_key' => ['nullable', 'string', 'max:255'],
            'webhook_secret' => ['nullable', 'string', 'max:255'],
            'success_url' => ['required', 'url', 'max:2048', 'starts_with:https://'],
            'cancel_url' => ['required', 'url', 'max:2048', 'starts_with:https://'],
            'default_currency' => ['required', 'string', 'size:3', Rule::in(['USD', 'EUR', 'GBP', 'AED', 'SAR', 'EGP'])],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'enabled' => $this->boolean('enabled'),
            'publishable_key' => $this->filled('publishable_key') ? trim((string) $this->input('publishable_key')) : null,
            'secret_key' => $this->filled('secret_key') ? trim((string) $this->input('secret_key')) : null,
            'webhook_secret' => $this->filled('webhook_secret') ? trim((string) $this->input('webhook_secret')) : null,
            'success_url' => trim((string) $this->input('success_url')),
            'cancel_url' => trim((string) $this->input('cancel_url')),
            'default_currency' => strtoupper(trim((string) $this->input('default_currency', 'USD'))),
        ]);
    }

    /**
     * @return array{
     *     enabled: bool,
     *     publishable_key: string|null,
     *     secret_key: string|null,
     *     webhook_secret: string|null,
     *     success_url: string,
     *     cancel_url: string,
     *     default_currency: string
     * }
     */
    public function settingsPayload(): array
    {
        /** @var array{
         *     enabled: bool,
         *     publishable_key: string|null,
         *     secret_key: string|null,
         *     webhook_secret: string|null,
         *     success_url: string,
         *     cancel_url: string,
         *     default_currency: string
         * } $validated */
        $validated = $this->validated();

        return $validated;
    }
}
