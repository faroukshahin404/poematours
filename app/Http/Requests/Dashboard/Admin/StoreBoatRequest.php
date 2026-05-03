<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreBoatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $default = Language::defaultSlug();
        $names = $this->input('name', []);
        if (! $this->filled('slug')) {
            $this->merge([
                'slug' => Str::slug((string) ($names[$default] ?? 'boat')),
            ]);
        }
    }

    /**
     * @return array<string, array<int, Unique|string>|string>
     */
    public function rules(): array
    {
        $default = Language::defaultSlug();
        $rules = [
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('boats', 'slug')],
            'name' => ['required', 'array'],
            'name.'.$default => ['required', 'string', 'max:500'],
            'boat' => ['required', 'array'],
            'boat.'.$default => ['required', 'string'],
            'suite' => ['required', 'array'],
            'suite.'.$default => ['required', 'string'],
            'food' => ['required', 'array'],
            'food.'.$default => ['required', 'string'],
            'wellness' => ['required', 'array'],
            'wellness.'.$default => ['required', 'string'],
            'boat_image' => ['nullable', 'image', 'max:5120'],
            'suite_image' => ['nullable', 'image', 'max:5120'],
            'food_image' => ['nullable', 'image', 'max:5120'],
            'wellness_image' => ['nullable', 'image', 'max:5120'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ];

        foreach (Language::query()->pluck('slug') as $slug) {
            if ((string) $slug === $default) {
                continue;
            }
            $rules['name.'.(string) $slug] = ['nullable', 'string', 'max:500'];
            $rules['boat.'.(string) $slug] = ['nullable', 'string'];
            $rules['suite.'.(string) $slug] = ['nullable', 'string'];
            $rules['food.'.(string) $slug] = ['nullable', 'string'];
            $rules['wellness.'.(string) $slug] = ['nullable', 'string'];
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->all();
            foreach (['name', 'boat', 'suite', 'food', 'wellness'] as $field) {
                foreach (array_keys($this->input($field, [])) as $key) {
                    if (! in_array((string) $key, $allowed, true)) {
                        $validator->errors()->add($field, __('Invalid language key in :field fields.', ['field' => $field]));

                        return;
                    }
                }
            }
        });
    }

    /**
     * @return array{slug: string, name: array<string, string>, boat: array<string, string>, suite: array<string, string>, food: array<string, string>, wellness: array<string, string>}
     */
    public function boatPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->flip()->all();

        $filterLocalized = function (array $value) use ($allowed): array {
            /** @var array<string, string> $result */
            $result = array_intersect_key($value, $allowed);

            return array_map(fn (mixed $v): string => trim((string) $v), $result);
        };

        return [
            'slug' => (string) ($validated['slug'] ?? ''),
            'name' => $filterLocalized($validated['name']),
            'boat' => $filterLocalized($validated['boat']),
            'suite' => $filterLocalized($validated['suite']),
            'food' => $filterLocalized($validated['food']),
            'wellness' => $filterLocalized($validated['wellness']),
        ];
    }
}
