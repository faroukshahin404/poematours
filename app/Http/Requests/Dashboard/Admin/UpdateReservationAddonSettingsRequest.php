<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationAddonSettingsRequest extends FormRequest
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
            'reservation_addon_groups' => ['nullable', 'array'],
            'reservation_addon_groups.*.code' => ['required', 'string', 'max:80'],
            'reservation_addon_groups.*.title' => ['required', 'string', 'max:120'],
            'reservation_addon_groups.*.selection_type' => ['required', Rule::in(['single', 'multiple'])],
            'reservation_addon_groups.*.is_required' => ['nullable', 'boolean'],
            'reservation_addon_groups.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'reservation_addon_groups.*.options' => ['required', 'array', 'min:1'],
            'reservation_addon_groups.*.options.*.code' => ['required', 'string', 'max:80'],
            'reservation_addon_groups.*.options.*.label' => ['required', 'string', 'max:120'],
            'reservation_addon_groups.*.options.*.price' => ['required', 'numeric', 'min:0'],
            'reservation_addon_groups.*.options.*.price_type' => ['required', Rule::in(['flat', 'per_person'])],
            'reservation_addon_groups.*.options.*.is_active' => ['nullable', 'boolean'],
            'reservation_addon_groups.*.options.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
