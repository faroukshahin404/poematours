<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\CrmContact;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCrmContactRequest extends FormRequest
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
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'status' => ['required', 'string', Rule::in(array_keys(CrmContact::statusLabels()))],
            'source' => ['sometimes', 'string', Rule::in(array_keys(CrmContact::sourceLabels()))],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer', 'exists:crm_services,id'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array{name: string, phone: string, email?: string|null, country_id: int, status: string, source?: string, notes?: string|null, service_ids?: array<int, int>}
     */
    public function contactPayload(): array
    {
        $validated = $this->validated();

        return [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'country_id' => (int) $validated['country_id'],
            'status' => $validated['status'],
            'source' => $validated['source'] ?? CrmContact::SOURCE_MANUAL,
            'service_ids' => array_values(array_unique(array_map('intval', $validated['service_ids'] ?? []))),
            'notes' => $validated['notes'] ?? null,
        ];
    }
}
