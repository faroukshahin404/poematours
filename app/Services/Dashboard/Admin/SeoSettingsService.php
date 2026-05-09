<?php

namespace App\Services\Dashboard\Admin;

use App\Models\Setting;

class SeoSettingsService
{
    private const GOOGLE_SEO_SCRIPT_KEY = 'seo_google_script';

    /**
     * Raw HTML snippets for the admin form (e.g. gtag.js).
     */
    public function googleSeoScriptForForm(): string
    {
        $raw = Setting::query()
            ->where('key', self::GOOGLE_SEO_SCRIPT_KEY)
            ->value('value');

        if (! is_string($raw)) {
            return '';
        }

        return $raw;
    }

    /**
     * Trimmed script markup for public pages, or null when unset.
     */
    public function googleSeoScriptForPublic(): ?string
    {
        $raw = $this->googleSeoScriptForForm();
        $trimmed = trim($raw);

        return $trimmed === '' ? null : $trimmed;
    }

    public function saveGoogleSeoScript(string $script, int $adminId): void
    {
        Setting::query()->updateOrCreate(
            ['key' => self::GOOGLE_SEO_SCRIPT_KEY],
            [
                'value' => $script,
                'created_by' => $adminId,
            ]
        );
    }
}
