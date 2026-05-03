<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
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
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'is_default' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array{name: string, is_default: bool}
     */
    public function currencyPayload(): array
    {
        $validated = $this->validated();

        return [
            'name' => $validated['name'],
            'is_default' => (bool) ($validated['is_default'] ?? false),
        ];
    }
}
