<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $default = Language::defaultSlug();
        $rules = [
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

    /**
     * @return array{name: array<string, string>}
     */
    public function categoryPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($slug) => (string) $slug)->flip()->all();
        $name = array_map('trim', array_intersect_key($validated['name'], $allowed));

        return [
            'name' => $name,
        ];
    }
}
