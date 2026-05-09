<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Resolves admin "icon" field values (Heroicon outline names or utility-class strings) to a safe slug for outline SVGs.
 */
final class HeroiconOutlineSlug
{
    public static function fromAdminIconField(string $raw): string
    {
        $raw = trim($raw);
        if ($raw === '') {
            return 'sparkles';
        }

        foreach (preg_split('/\s+/', $raw) as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }
            if (preg_match('/^[a-z][a-z0-9]*(?:-[a-z0-9]+)+$/i', $part)) {
                return self::sanitize(Str::lower($part));
            }
        }

        if (! str_contains($raw, ' ')) {
            $stripped = preg_replace('/^(heroicons?|hi)[-_.]?(?:o|outline|m|mini|s|solid)[-_.]?/i', '', $raw);
            $stripped = trim($stripped);
            if (preg_match('/^[a-z][a-z0-9]*(?:-[a-z0-9]+)*$/i', $stripped)) {
                return self::sanitize(Str::lower($stripped));
            }
        }

        return 'sparkles';
    }

    private static function sanitize(string $slug): string
    {
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        $slug = trim($slug, '-');

        if ($slug === '' || strlen($slug) > 80) {
            return 'sparkles';
        }

        return $slug;
    }
}
