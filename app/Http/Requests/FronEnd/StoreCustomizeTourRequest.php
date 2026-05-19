<?php

namespace App\Http\Requests\FronEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomizeTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $interests = array_filter((array) $this->input('interests', []), fn (mixed $value): bool => is_string($value) && $value !== '');

        $this->merge([
            'email' => $this->filled('email') ? strtolower(trim((string) $this->input('email'))) : null,
            'phone' => $this->filled('phone') ? trim((string) $this->input('phone')) : null,
            'full_name' => $this->filled('full_name') ? trim((string) $this->input('full_name')) : null,
            'destinations' => $this->filled('destinations') ? trim((string) $this->input('destinations')) : null,
            'notes' => $this->filled('notes') ? trim((string) $this->input('notes')) : null,
            'interests' => array_values($interests),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $interestOptions = [
            'history',
            'nile-cruise',
            'desert-adventure',
            'red-sea',
            'family-time',
            'honeymoon',
            'luxury',
            'culture-food',
        ];

        return [
            'package_id' => ['nullable', 'integer', Rule::exists('packages', 'id')],
            'full_name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email:rfc,dns', 'max:255', 'required_without:phone'],
            'phone' => ['nullable', 'string', 'max:40', 'required_without:email'],
            'adults' => ['nullable', 'integer', 'min:1', 'max:50'],
            'children' => ['nullable', 'integer', 'min:0', 'max:50'],
            'arrival_date' => ['nullable', 'date', 'after_or_equal:today'],
            'departure_date' => ['nullable', 'date', 'after_or_equal:arrival_date'],
            'duration_days' => ['nullable', 'integer', 'min:1', 'max:60'],
            'destinations' => ['nullable', 'string', 'max:255'],
            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', Rule::in($interestOptions)],
            'accommodation_style' => ['nullable', 'string', 'max:100'],
            'budget_range' => ['nullable', 'string', 'max:100'],
            'need_guide' => ['nullable', 'boolean'],
            'need_transportation' => ['nullable', 'boolean'],
            'preferred_contact_method' => ['nullable', Rule::in(['email', 'phone', 'whatsapp'])],
            'notes' => ['nullable', 'string', 'max:3000'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function requestPayload(): array
    {
        $validated = $this->validated();

        return [
            'package_id' => $validated['package_id'] ?? null,
            'full_name' => $validated['full_name'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'adults' => $validated['adults'] ?? null,
            'children' => $validated['children'] ?? null,
            'arrival_date' => $validated['arrival_date'] ?? null,
            'departure_date' => $validated['departure_date'] ?? null,
            'duration_days' => $validated['duration_days'] ?? null,
            'destinations' => $validated['destinations'] ?? null,
            'interests' => $validated['interests'] ?? [],
            'accommodation_style' => $validated['accommodation_style'] ?? null,
            'budget_range' => $validated['budget_range'] ?? null,
            'need_guide' => $this->boolean('need_guide') ? 1 : null,
            'need_transportation' => $this->boolean('need_transportation') ? 1 : null,
            'preferred_contact_method' => $validated['preferred_contact_method'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];
    }
}
