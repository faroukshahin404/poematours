<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $default = Language::defaultSlug();
        $rules = [
            'destination_id' => ['required', 'integer', 'exists:destinations,id'],
            'name' => ['required', 'array'],
            'name.'.$default => ['required', 'string', 'max:500'],
            'description' => ['required', 'array'],
            'description.'.$default => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
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
     * @return array{destination_id: int, name: array<string, string>, description: array<string, string>}
     */
    public function activityPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->flip()->all();
        $name = array_map('trim', array_intersect_key($validated['name'], $allowed));
        $description = array_map('trim', array_intersect_key($validated['description'], $allowed));

        return [
            'destination_id' => (int) $validated['destination_id'],
            'name' => $name,
            'description' => $description,
        ];
    }
}
