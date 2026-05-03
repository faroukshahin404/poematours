<?php

namespace App\Http\Requests\FronEnd;

use App\Services\Settings\SettingService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $normalizedAddons = [];
        foreach ((array) $this->input('addons', []) as $groupCode => $value) {
            if (! is_string($groupCode) || $groupCode === '') {
                continue;
            }

            if (is_array($value)) {
                $normalizedAddons[$groupCode] = array_values(array_filter($value, fn (mixed $item): bool => is_string($item) && $item !== ''));
                continue;
            }

            if (is_string($value) && $value !== '') {
                $normalizedAddons[$groupCode] = $value;
            }
        }

        $this->merge([
            'full_name' => trim((string) $this->input('full_name')),
            'email' => strtolower(trim((string) $this->input('email'))),
            'phone' => $this->filled('phone') ? trim((string) $this->input('phone')) : null,
            'country' => $this->filled('country') ? trim((string) $this->input('country')) : null,
            'destinations' => $this->filled('destinations') ? trim((string) $this->input('destinations')) : null,
            'tour_style' => $this->filled('tour_style') ? trim((string) $this->input('tour_style')) : null,
            'hotel_category' => $this->filled('hotel_category') ? trim((string) $this->input('hotel_category')) : null,
            'notes' => $this->filled('notes') ? trim((string) $this->input('notes')) : null,
            'addons' => $normalizedAddons,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'country' => ['nullable', 'string', 'max:120'],
            'preferred_contact_method' => ['nullable', Rule::in(['email', 'phone', 'whatsapp'])],
            'adults' => ['required', 'integer', 'min:1', 'max:50'],
            'children' => ['nullable', 'integer', 'min:0', 'max:50'],
            'arrival_date' => ['nullable', 'date', 'after_or_equal:today'],
            'departure_date' => ['nullable', 'date', 'after_or_equal:arrival_date'],
            'duration_days' => ['nullable', 'integer', 'min:1', 'max:60'],
            'destinations' => ['nullable', 'string', 'max:255'],
            'tour_style' => ['nullable', 'string', 'max:120'],
            'hotel_category' => ['nullable', 'string', 'max:120'],
            'need_transfers' => ['nullable', 'boolean'],
            'need_domestic_flights' => ['nullable', 'boolean'],
            'estimated_total' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:3000'],
            'addons' => ['nullable', 'array'],
        ];

        foreach ($this->addonGroups() as $group) {
            $groupCode = (string) ($group['code'] ?? '');
            if ($groupCode === '') {
                continue;
            }

            $optionCodes = [];
            foreach ((array) ($group['options'] ?? []) as $option) {
                $code = (string) ($option['code'] ?? '');
                if ($code !== '') {
                    $optionCodes[] = $code;
                }
            }

            $isRequired = (bool) ($group['is_required'] ?? false);
            $selectionType = (string) ($group['selection_type'] ?? 'multiple');
            if ($selectionType === 'single') {
                $rules["addons.$groupCode"] = array_filter([
                    $isRequired ? 'required' : 'nullable',
                    'string',
                    Rule::in($optionCodes),
                ]);
            } else {
                $rules["addons.$groupCode"] = array_filter([
                    $isRequired ? 'required' : 'nullable',
                    'array',
                    $isRequired ? 'min:1' : null,
                ]);
                $rules["addons.$groupCode.*"] = ['string', Rule::in($optionCodes), 'distinct'];
            }
        }

        return $rules;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $groups = $this->addonGroups();
            $allowed = collect($groups)->pluck('code')->filter(fn (mixed $value): bool => is_string($value) && $value !== '')->values()->all();
            $submitted = array_keys((array) $this->input('addons', []));

            foreach ($submitted as $groupCode) {
                if (! in_array($groupCode, $allowed, true)) {
                    $validator->errors()->add("addons.$groupCode", 'This add-on group is not available.');
                }
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function reservationPayload(): array
    {
        $validated = $this->validated();

        return [
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'country' => $validated['country'] ?? null,
            'preferred_contact_method' => $validated['preferred_contact_method'] ?? null,
            'adults' => (int) $validated['adults'],
            'children' => (int) ($validated['children'] ?? 0),
            'arrival_date' => $validated['arrival_date'] ?? null,
            'departure_date' => $validated['departure_date'] ?? null,
            'duration_days' => $validated['duration_days'] ?? null,
            'destinations' => $validated['destinations'] ?? null,
            'tour_style' => $validated['tour_style'] ?? null,
            'hotel_category' => $validated['hotel_category'] ?? null,
            'need_transfers' => $this->boolean('need_transfers'),
            'need_domestic_flights' => $this->boolean('need_domestic_flights'),
            'estimated_total' => (float) $validated['estimated_total'],
            'notes' => $validated['notes'] ?? null,
            'addons' => $validated['addons'] ?? [],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function addonGroups(): array
    {
        return app(SettingService::class)->activeReservationAddonGroups();
    }
}
