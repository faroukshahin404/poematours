<?php

namespace App\Services\Frontend;

use App\Models\Activity;
use App\Models\Blog;
use App\Models\TravelPackage;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;

class SitemapService
{
    private const CACHE_KEY = 'frontend.sitemap.entries.v1';

    private const CACHE_TTL_SECONDS = 3600;

    /**
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    public function entries(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, fn (): array => $this->buildEntries());
    }

    public function forgetCachedEntries(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    private function buildEntries(): array
    {
        $entries = [];

        foreach ($this->staticMarketingUrls() as $row) {
            $entries[] = $row;
        }

        foreach ($this->activityUrls() as $row) {
            $entries[] = $row;
        }

        foreach ($this->packageUrls() as $row) {
            $entries[] = $row;
        }

        foreach ($this->blogUrls() as $row) {
            $entries[] = $row;
        }

        return $entries;
    }

    /**
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    private function staticMarketingUrls(): array
    {
        return [
            ['loc' => route('home'), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('about.us'), 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => route('destinations.index'), 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => route('our.journeys'), 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => route('terms.of.use'), 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => route('privacy.policy'), 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => route('packages.index'), 'changefreq' => 'daily', 'priority' => '0.95'],
            ['loc' => route('search'), 'changefreq' => 'weekly', 'priority' => '0.85'],
            ['loc' => route('packages.gallery'), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => route('customize.create'), 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['loc' => route('reservation.create'), 'changefreq' => 'monthly', 'priority' => '0.75'],
        ];
    }

    /**
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    private function activityUrls(): array
    {
        return Activity::query()
            ->orderBy('slug')
            ->get(['slug', 'updated_at'])
            ->map(function (Activity $activity): array {
                $slug = trim((string) $activity->slug);
                if ($slug === '') {
                    return [];
                }

                return [
                    'loc' => route('activities.show', $slug),
                    'lastmod' => $this->lastModifiedW3c($activity->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.75',
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    private function packageUrls(): array
    {
        return TravelPackage::query()
            ->orderBy('slug')
            ->get(['slug', 'updated_at'])
            ->map(function (TravelPackage $package): array {
                $slug = trim((string) $package->slug);
                if ($slug === '') {
                    return [];
                }

                return [
                    'loc' => route('packages.show', $slug),
                    'lastmod' => $this->lastModifiedW3c($package->updated_at),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return list<array{loc: string, lastmod?: string, changefreq?: string, priority?: string}>
     */
    private function blogUrls(): array
    {
        return Blog::query()
            ->orderBy('slug')
            ->get(['slug', 'updated_at'])
            ->map(function (Blog $blog): array {
                $slug = trim((string) $blog->slug);
                if ($slug === '') {
                    return [];
                }

                return [
                    'loc' => route('our.journeys.show', $slug),
                    'lastmod' => $this->lastModifiedW3c($blog->updated_at),
                    'changefreq' => 'monthly',
                    'priority' => '0.75',
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function lastModifiedW3c(mixed $value): ?string
    {
        if ($value instanceof CarbonInterface) {
            return $value->utc()->toAtomString();
        }

        return null;
    }
}
