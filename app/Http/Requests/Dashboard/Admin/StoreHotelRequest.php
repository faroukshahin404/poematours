<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StoreHotelRequest extends FormRequest
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
                'slug' => Str::slug((string) ($names[$default] ?? 'hotel')),
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
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('hotels', 'slug')],
            'destination_id' => ['required', 'integer', 'exists:destinations,id'],
            'stars' => ['required', 'integer', 'min:1', 'max:7'],
            'name' => ['required', 'array'],
            'name.'.$default => ['required', 'string', 'max:500'],
            'description' => ['required', 'array'],
            'description.'.$default => ['required', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ];

        foreach (Language::query()->pluck('slug') as $slug) {
            if ((string) $slug === $default) {
                continue;
            }
            $rules['name.'.(string) $slug] = ['nullable', 'string', 'max:500'];
            $rules['description.'.(string) $slug] = ['nullable', 'string'];
        }

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->all();

            foreach (array_keys($this->input('name', [])) as $key) {
                if (! in_array((string) $key, $allowed, true)) {
                    $validator->errors()->add('name', __('Invalid language key in name fields.'));

                    return;
                }
            }

            foreach (array_keys($this->input('description', [])) as $key) {
                if (! in_array((string) $key, $allowed, true)) {
                    $validator->errors()->add('description', __('Invalid language key in description fields.'));

                    return;
                }
            }
        });
    }

    /**
     * @return array{slug: string, destination_id: int, stars: int, name: array<string, string>, description: array<string, string>}
     */
    public function hotelPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->flip()->all();

        /** @var array<string, string> $name */
        $name = array_intersect_key($validated['name'], $allowed);
        $name = array_map(fn (mixed $v): string => trim((string) $v), $name);

        /** @var array<string, string> $description */
        $description = array_intersect_key($validated['description'], $allowed);
        $description = array_map(fn (mixed $v): string => trim((string) $v), $description);

        return [
            'slug' => (string) ($validated['slug'] ?? ''),
            'destination_id' => (int) $validated['destination_id'],
            'stars' => (int) $validated['stars'],
            'name' => $name,
            'description' => $description,
        ];
    }
}
