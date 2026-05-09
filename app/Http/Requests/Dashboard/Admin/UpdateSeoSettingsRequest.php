<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeoSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'google_seo_script' => ['nullable', 'string', 'max:65000'],
        ];
    }

    public function settingsPayload(): array
    {
        /** @var array{google_seo_script?: string|null} $validated */
        $validated = $this->validated();

        return [
            'google_seo_script' => (string) ($validated['google_seo_script'] ?? ''),
        ];
    }
}
