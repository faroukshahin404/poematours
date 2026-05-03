<?php

namespace App\Models\Concerns;

use App\Models\Language;

trait HasJsonLocalizedName
{
    /**
     * @return array<string, string>
     */
    public static function decodeNameJson(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }
        if (is_array($value)) {
            return $value;
        }
        $decoded = json_decode((string) $value, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Localized display name for the current session language (language slug in session `locale`).
     *
     * @param  mixed  $value  Raw attribute from storage
     */
    public function getNameAttribute(mixed $value): string
    {
        $translations = static::decodeNameJson($value);
        $slug = session('locale', Language::defaultSlug());

        if (isset($translations[$slug]) && $translations[$slug] !== '') {
            return (string) $translations[$slug];
        }

        $first = reset($translations);

        return $first !== false ? (string) $first : '';
    }

    /**
     * @param  array<string, string>|string|null  $value
     */
    public function setNameAttribute(mixed $value): void
    {
        if (is_array($value)) {
            $this->attributes['name'] = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['name'] = $value;
        }
    }

    /**
     * All translations keyed by language slug (for admin forms / APIs).
     *
     * @return array<string, string>
     */
    public function nameTranslations(): array
    {
        return static::decodeNameJson($this->getAttributes()['name'] ?? null);
    }

    public function labelForDefaultLanguage(): string
    {
        $translations = $this->nameTranslations();
        $default = Language::defaultSlug();

        return (string) ($translations[$default] ?? reset($translations) ?: '');
    }
}
