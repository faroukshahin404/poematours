<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Boat;
use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdateBoatRequest extends FormRequest
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
        /** @var Boat $boat */
        $boat = $this->route('boat');

        $rules = [
            'slug' => ['required', 'string', 'max:255', Rule::unique('boats', 'slug')->ignore($boat->id)],
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
            'remove_boat_image' => ['nullable', 'boolean'],
            'remove_suite_image' => ['nullable', 'boolean'],
            'remove_food_image' => ['nullable', 'boolean'],
            'remove_wellness_image' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
            'remove_media_ids' => ['nullable', 'array'],
            'remove_media_ids.*' => ['integer', 'exists:media,id'],
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
     * @return array{slug: string, name: array<string, string>, boat: array<string, string>, suite: array<string, string>, food: array<string, string>, wellness: array<string, string>, remove_media_ids: array<int, int>, remove_boat_image: bool, remove_suite_image: bool, remove_food_image: bool, remove_wellness_image: bool}
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
            'slug' => $validated['slug'],
            'name' => $filterLocalized($validated['name']),
            'boat' => $filterLocalized($validated['boat']),
            'suite' => $filterLocalized($validated['suite']),
            'food' => $filterLocalized($validated['food']),
            'wellness' => $filterLocalized($validated['wellness']),
            'remove_media_ids' => array_map(fn (mixed $v): int => (int) $v, $validated['remove_media_ids'] ?? []),
            'remove_boat_image' => (bool) ($validated['remove_boat_image'] ?? false),
            'remove_suite_image' => (bool) ($validated['remove_suite_image'] ?? false),
            'remove_food_image' => (bool) ($validated['remove_food_image'] ?? false),
            'remove_wellness_image' => (bool) ($validated['remove_wellness_image'] ?? false),
        ];
    }
}
