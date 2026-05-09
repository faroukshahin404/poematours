<?php

namespace App\Services\Dashboard\Admin;

use App\Models\Setting;

class ContactSettingsService
{
    private const CONTACT_SETTINGS_KEY = 'contact_settings';

    /**
     * @return array{
     *     email: string|null,
     *     phone_country_1: string,
     *     phone_number_1: string|null,
     *     phone_country_2: string,
     *     phone_number_2: string|null,
     *     facebook_url: string|null,
     *     instagram_url: string|null,
     *     tripadvisor_url: string|null,
     *     tiktok_url: string|null,
     *     x_url: string|null,
     *     linkedin_url: string|null,
     *     social_email: string|null
     * }
     */
    public function contactSettingsForForm(): array
    {
        $rawValue = Setting::query()
            ->where('key', self::CONTACT_SETTINGS_KEY)
            ->value('value');

        if (! is_string($rawValue) || trim($rawValue) === '') {
            return $this->defaultSettings();
        }

        $decoded = json_decode($rawValue, true);
        if (! is_array($decoded)) {
            return $this->defaultSettings();
        }

        return array_merge($this->defaultSettings(), [
            'email' => $this->nullableString($decoded['email'] ?? null),
            'phone_country_1' => $this->nullableString($decoded['phone_country_1'] ?? null) ?? 'Egypt',
            'phone_number_1' => $this->nullableString($decoded['phone_number_1'] ?? null),
            'phone_country_2' => $this->nullableString($decoded['phone_country_2'] ?? null) ?? 'USA',
            'phone_number_2' => $this->nullableString($decoded['phone_number_2'] ?? null),
            'facebook_url' => $this->nullableString($decoded['facebook_url'] ?? null),
            'instagram_url' => $this->nullableString($decoded['instagram_url'] ?? null),
            'tripadvisor_url' => $this->nullableString($decoded['tripadvisor_url'] ?? null),
            'tiktok_url' => $this->nullableString($decoded['tiktok_url'] ?? null),
            'x_url' => $this->nullableString($decoded['x_url'] ?? null),
            'linkedin_url' => $this->nullableString($decoded['linkedin_url'] ?? null),
            'social_email' => $this->nullableString($decoded['social_email'] ?? null),
        ]);
    }

    /**
     * @param  array{
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
     * }  $payload
     */
    public function saveContactSettings(array $payload, int $adminId): void
    {
        $normalized = [
            'email' => $this->nullableString($payload['email'] ?? null),
            'phone_country_1' => $this->nullableString($payload['phone_country_1'] ?? null) ?? 'Egypt',
            'phone_number_1' => $this->nullableString($payload['phone_number_1'] ?? null),
            'phone_country_2' => $this->nullableString($payload['phone_country_2'] ?? null) ?? 'USA',
            'phone_number_2' => $this->nullableString($payload['phone_number_2'] ?? null),
            'facebook_url' => $this->nullableString($payload['facebook_url'] ?? null),
            'instagram_url' => $this->nullableString($payload['instagram_url'] ?? null),
            'tripadvisor_url' => $this->nullableString($payload['tripadvisor_url'] ?? null),
            'tiktok_url' => $this->nullableString($payload['tiktok_url'] ?? null),
            'x_url' => $this->nullableString($payload['x_url'] ?? null),
            'linkedin_url' => $this->nullableString($payload['linkedin_url'] ?? null),
            'social_email' => $this->nullableString($payload['social_email'] ?? null),
        ];

        Setting::query()->updateOrCreate(
            ['key' => self::CONTACT_SETTINGS_KEY],
            [
                'value' => json_encode($normalized, JSON_UNESCAPED_SLASHES),
                'created_by' => $adminId,
            ]
        );
    }

    /**
     * @return array{
     *     email: string|null,
     *     phone_country_1: string,
     *     phone_number_1: string|null,
     *     phone_country_2: string,
     *     phone_number_2: string|null,
     *     facebook_url: string|null,
     *     instagram_url: string|null,
     *     tripadvisor_url: string|null,
     *     tiktok_url: string|null,
     *     x_url: string|null,
     *     linkedin_url: string|null,
     *     social_email: string|null
     * }
     */
    private function defaultSettings(): array
    {
        return [
            'email' => null,
            'phone_country_1' => 'Egypt',
            'phone_number_1' => null,
            'phone_country_2' => 'USA',
            'phone_number_2' => null,
            'facebook_url' => null,
            'instagram_url' => null,
            'tripadvisor_url' => null,
            'tiktok_url' => null,
            'x_url' => null,
            'linkedin_url' => null,
            'social_email' => null,
        ];
    }

    private function nullableString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
