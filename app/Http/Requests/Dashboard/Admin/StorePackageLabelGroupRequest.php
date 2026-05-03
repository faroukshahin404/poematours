<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class StorePackageLabelGroupRequest extends FormRequest
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
                'slug' => Str::slug((string) ($names[$default] ?? 'package-label-group')),
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
            'slug' => ['required', 'string', 'max:255', Rule::unique('package_label_groups', 'slug')],
            'name' => ['required', 'array'],
            'name.'.$default => ['required', 'string', 'max:500'],
        ];

        foreach (Language::query()->pluck('slug') as $slug) {
            if ((string) $slug === $default) {
                continue;
            }
            $rules['name.'.(string) $slug] = ['nullable', 'string', 'max:500'];
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
        });
    }

    /**
     * @return array{slug: string, name: array<string, string>}
     */
    public function groupPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->flip()->all();
        /** @var array<string, string> $name */
        $name = array_intersect_key($validated['name'], $allowed);
        $name = array_map(fn (mixed $v): string => trim((string) $v), $name);

        return [
            'slug' => $validated['slug'],
            'name' => $name,
        ];
    }
}
