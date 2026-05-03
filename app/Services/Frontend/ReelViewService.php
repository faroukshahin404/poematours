<?php

namespace App\Services\Frontend;

use App\Models\Language;
use App\Models\Reel;
use Illuminate\Support\Collection;

class ReelViewService
{
    /**
     * @return Collection<int, array{video: string, snapshot: string, title: string, description: string}>
     */
    public function random(int $limit = 8): Collection
    {
        $locale = session('locale', Language::defaultSlug());
        $fallbackSnapshot = asset('assets/images/placeholders/banner.jpeg');

        return Reel::query()
            ->inRandomOrder()
            ->limit($limit)
            ->get(['name', 'description', 'video_url'])
            ->map(function (Reel $reel) use ($locale, $fallbackSnapshot): array {
                $nameTranslations = $reel->nameTranslations();
                $descriptionTranslations = $reel->descriptionTranslations();

                $title = $this->translatedValue($nameTranslations, $locale, 'Reel');
                $description = $this->translatedValue($descriptionTranslations, $locale, '');

                return [
                    'video' => $reel->videoPublicUrl() ?? '',
                    'snapshot' => $fallbackSnapshot,
                    'title' => $title,
                    'description' => $description,
                ];
            })
            ->filter(fn (array $reel): bool => $reel['video'] !== '')
            ->values();
    }

    /**
     * @param  array<string, string>  $translations
     */
    private function translatedValue(array $translations, string $locale, string $fallback): string
    {
        if (isset($translations[$locale]) && trim($translations[$locale]) !== '') {
            return trim($translations[$locale]);
        }

        $first = collect($translations)
            ->map(fn (string $value): string => trim($value))
            ->first(fn (string $value): bool => $value !== '');

        return $first ?: $fallback;
    }
}
