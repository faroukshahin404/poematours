<?php

namespace App\Http\Requests\Dashboard\Admin;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class StorePackageInclusionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $default = Language::defaultSlug();

        return [
            'name' => ['required', 'array'],
            'name.'.$default => ['required', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array{name: array<string, string>, icon: ?string}
     */
    public function inclusionPayload(): array
    {
        $validated = $this->validated();
        $allowed = Language::query()->pluck('slug')->map(fn ($s) => (string) $s)->flip()->all();

        return [
            'name' => array_map('trim', array_intersect_key($validated['name'], $allowed)),
            'icon' => isset($validated['icon']) ? trim((string) $validated['icon']) : null,
        ];
    }
}
