<?php

namespace App\Http\Requests\FronEnd;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebsiteContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'country_prefix' => ['nullable', 'string', 'max:20'],
            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function payload(): array
    {
        $validated = $this->validated();

        return [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'country_prefix' => $validated['country_prefix'] ?? null,
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'notes' => $validated['notes'] ?? null,
        ];
    }
}
