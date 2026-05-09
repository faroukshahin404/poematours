<?php

namespace App\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactSettingsRequest extends FormRequest
{
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
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'phone_country_1' => ['required', 'string', 'max:100'],
            'phone_number_1' => ['required', 'string', 'max:50'],
            'phone_country_2' => ['required', 'string', 'max:100'],
            'phone_number_2' => ['required', 'string', 'max:50'],
            'facebook_url' => ['nullable', 'url', 'max:2048'],
            'instagram_url' => ['nullable', 'url', 'max:2048'],
            'tripadvisor_url' => ['nullable', 'url', 'max:2048'],
            'tiktok_url' => ['nullable', 'url', 'max:2048'],
            'x_url' => ['nullable', 'url', 'max:2048'],
            'linkedin_url' => ['nullable', 'url', 'max:2048'],
            'social_email' => ['nullable', 'email:rfc,dns', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => trim((string) $this->input('email')),
            'phone_country_1' => trim((string) $this->input('phone_country_1')),
            'phone_number_1' => trim((string) $this->input('phone_number_1')),
            'phone_country_2' => trim((string) $this->input('phone_country_2')),
            'phone_number_2' => trim((string) $this->input('phone_number_2')),
            'facebook_url' => $this->nullableTrimmed('facebook_url'),
            'instagram_url' => $this->nullableTrimmed('instagram_url'),
            'tripadvisor_url' => $this->nullableTrimmed('tripadvisor_url'),
            'tiktok_url' => $this->nullableTrimmed('tiktok_url'),
            'x_url' => $this->nullableTrimmed('x_url'),
            'linkedin_url' => $this->nullableTrimmed('linkedin_url'),
            'social_email' => $this->nullableTrimmed('social_email'),
        ]);
    }

    /**
     * @return array{
     *     email: string,
     *     phone_country_1: string,
     *     phone_number_1: string,
     *     phone_country_2: string,
     *     phone_number_2: string,
     *     facebook_url: string|null,
     *     instagram_url: string|null,
     *     tripadvisor_url: string|null,
     *     tiktok_url: string|null,
     *     x_url: string|null,
     *     linkedin_url: string|null,
     *     social_email: string|null
     * }
     */
    public function settingsPayload(): array
    {
        /** @var array{
         *     email: string,
         *     phone_country_1: string,
         *     phone_number_1: string,
         *     phone_country_2: string,
         *     phone_number_2: string,
         *     facebook_url: string|null,
         *     instagram_url: string|null,
         *     tripadvisor_url: string|null,
         *     tiktok_url: string|null,
         *     x_url: string|null,
         *     linkedin_url: string|null,
         *     social_email: string|null
         * } $validated */
        $validated = $this->validated();

        return $validated;
    }

    private function nullableTrimmed(string $field): ?string
    {
        if (! $this->filled($field)) {
            return null;
        }

        $value = trim((string) $this->input($field));

        return $value === '' ? null : $value;
    }
}
