<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use App\Models\PackageLabel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdatePackageLabelRequest extends FormRequest
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
                'slug' => Str::slug((string) ($names[$default] ?? 'package-label')),
            ]);
        }
    }

    /**
     * @return array<string, array<int, Unique|string>|string>
     */
    public function rules(): array
    {
        $default = Language::defaultSlug();
        /** @var PackageLabel $packageLabel */
        $packageLabel = $this->route('package_label');
        $groupId = (int) $this->input('package_label_group_id');

        $rules = [
            'package_label_group_id' => ['required', 'integer', 'exists:package_label_groups,id'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('package_labels', 'slug')
                    ->where(fn ($query) => $query->where('package_label_group_id', $groupId))
                    ->ignore($packageLabel->id),
            ],
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
     * @return array{slug: string, name: array<string, string>, package_label_group_id: int}
     */
    public function labelPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->flip()->all();
        /** @var array<string, string> $name */
        $name = array_intersect_key($validated['name'], $allowed);
        $name = array_map(fn (mixed $v): string => trim((string) $v), $name);

        return [
            'slug' => $validated['slug'],
            'name' => $name,
            'package_label_group_id' => (int) $validated['package_label_group_id'],
        ];
    }
}
