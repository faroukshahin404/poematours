<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $default = Language::defaultSlug();
        $rules = [
            'blog_category_id' => ['required', 'integer', 'exists:blog_categories,id'],
            'title' => ['required', 'array'],
            'title.'.$default => ['required', 'string', 'max:500'],
            'details' => ['required', 'array'],
            'details.'.$default => ['required', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'views_count' => ['nullable', 'integer', 'min:0'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
            'remove_media_ids' => ['nullable', 'array'],
            'remove_media_ids.*' => ['integer', 'exists:media,id'],
        ];

        foreach (Language::query()->pluck('slug') as $slug) {
            if ((string) $slug === $default) {
                continue;
            }
            $rules['title.'.(string) $slug] = ['nullable', 'string', 'max:500'];
            $rules['details.'.(string) $slug] = ['nullable', 'string'];
        }

        return $rules;
    }

    /**
     * @return array{blog_category_id: int, title: array<string, string>, details: array<string, string>, is_featured: bool, views_count: int, remove_media_ids: array<int, int>}
     */
    public function blogPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($slug) => (string) $slug)->flip()->all();

        return [
            'blog_category_id' => (int) $validated['blog_category_id'],
            'title' => array_map('trim', array_intersect_key($validated['title'], $allowed)),
            'details' => array_map('trim', array_intersect_key($validated['details'], $allowed)),
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'views_count' => (int) ($validated['views_count'] ?? 0),
            'remove_media_ids' => array_map(fn (mixed $id): int => (int) $id, $validated['remove_media_ids'] ?? []),
        ];
    }
}
