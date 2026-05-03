<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReelRequest extends FormRequest
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
            'description' => ['required', 'array'],
            'description.'.$default => ['required', 'string'],
            'video_url' => ['nullable', 'string', 'max:2048'],
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

    /**
     * @return array{name: array<string, string>, description: array<string, string>, video_url: string|null}
     */
    public function reelPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($slug) => (string) $slug)->flip()->all();

        return [
            'name' => array_map('trim', array_intersect_key($validated['name'], $allowed)),
            'description' => array_map('trim', array_intersect_key($validated['description'], $allowed)),
            'video_url' => isset($validated['video_url']) && $validated['video_url'] !== ''
                ? trim((string) $validated['video_url'])
                : null,
        ];
    }
}
