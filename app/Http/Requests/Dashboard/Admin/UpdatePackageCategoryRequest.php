<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use App\Models\PackageCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdatePackageCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
                'slug' => Str::slug((string) ($names[$default] ?? 'package-category')),
            ]);
        }
    }

    /**
     * @return array<string, array<int, Unique|string>|string>
     */
    public function rules(): array
    {
        $default = Language::defaultSlug();
        /** @var PackageCategory $packageCategory */
        $packageCategory = $this->route('package_category');

        $rules = [
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('package_categories', 'slug')->ignore($packageCategory->id),
            ],
            'name' => ['required', 'array'],
            'name.'.$default => ['required', 'string', 'max:500'],
            'title' => ['required', 'array'],
            'title.'.$default => ['required', 'string', 'max:500'],
            'description' => ['required', 'array'],
            'description.'.$default => ['required', 'string', 'max:2000'],
            'image' => ['sometimes', 'nullable', 'image', 'max:5120'],
        ];

        foreach (Language::query()->pluck('slug') as $slug) {
            if ((string) $slug === $default) {
                continue;
            }
            $rules['name.'.(string) $slug] = ['nullable', 'string', 'max:500'];
            $rules['title.'.(string) $slug] = ['nullable', 'string', 'max:500'];
            $rules['description.'.(string) $slug] = ['nullable', 'string', 'max:2000'];
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
            foreach (array_keys($this->input('title', [])) as $key) {
                if (! in_array((string) $key, $allowed, true)) {
                    $validator->errors()->add('title', __('Invalid language key in title fields.'));

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
     * @return array{slug: string, name: array<string, string>, title: array<string, string>, description: array<string, string>}
     */
    public function categoryPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->flip()->all();
        /** @var array<string, string> $name */
        $name = array_intersect_key($validated['name'], $allowed);
        $name = array_map(fn (mixed $v): string => trim((string) $v), $name);
        /** @var array<string, string> $title */
        $title = array_intersect_key($validated['title'], $allowed);
        $title = array_map(fn (mixed $v): string => trim((string) $v), $title);
        /** @var array<string, string> $description */
        $description = array_intersect_key($validated['description'], $allowed);
        $description = array_map(fn (mixed $v): string => trim((string) $v), $description);

        return [
            'slug' => $validated['slug'],
            'name' => $name,
            'title' => $title,
            'description' => $description,
        ];
    }
}
