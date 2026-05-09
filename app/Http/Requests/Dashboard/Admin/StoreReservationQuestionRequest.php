<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Http\Requests\Dashboard\Admin\Concerns\BuildsReservationQuestionPayload;
use App\Models\ReservationQuestion;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationQuestionRequest extends FormRequest
{
    use BuildsReservationQuestionPayload;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $default = $this->defaultLanguageSlug();
        $rules = [
            'title' => ['required', 'array'],
            'title.'.$default => ['required', 'string', 'max:500'],
            'description' => ['nullable', 'array'],
            'description.'.$default => ['nullable', 'string', 'max:5000'],
            'type' => ['required', 'string', 'in:'.implode(',', ReservationQuestion::TYPES)],
            'is_package_reservation' => ['nullable', 'boolean'],
            'is_reservation_page' => ['nullable', 'boolean'],
            'options' => ['nullable', 'array'],
            'options.*.label' => ['required_with:options', 'array'],
            'options.*.label.'.$default => ['required_with:options', 'string', 'max:500'],
            'options.*.added_price' => ['nullable', 'numeric', 'min:0'],
        ];

        foreach ($this->languageSlugs() as $slug) {
            if ($slug === $default) {
                continue;
            }

            $rules['title.'.$slug] = ['nullable', 'string', 'max:500'];
            $rules['description.'.$slug] = ['nullable', 'string', 'max:5000'];
            $rules['options.*.label.'.$slug] = ['nullable', 'string', 'max:500'];
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $this->ensureValidLanguageKeys($validator);

            $type = (string) $this->input('type');
            $needsOptions = in_array($type, ['select', 'multi_select'], true);
            $options = $this->input('options', []);

            if ($needsOptions && (! is_array($options) || count($options) === 0)) {
                $validator->errors()->add('options', __('At least one option is required for the selected type.'));
            }
        });
    }
}
