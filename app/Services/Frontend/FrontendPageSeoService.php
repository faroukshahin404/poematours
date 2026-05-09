<?php

namespace App\Services\Frontend;

use App\Models\Page;
use Illuminate\Support\Str;

class FrontendPageSeoService
{
    /**
     * @return array{
     *     title: string,
     *     meta_description: string|null,
     *     meta_keywords: string,
     *     canonical_url: string|null,
     *     og_title: string,
     *     og_description: string|null,
     *     og_type: string,
     *     og_url: string|null,
     *     og_image: string|null
     * }
     */
    public function defaults(): array
    {
        return [
            'title' => 'Poema Tours | Enter Egypt',
            'meta_description' => null,
            'meta_keywords' => '',
            'canonical_url' => null,
            'og_title' => 'Poema Tours | Enter Egypt',
            'og_description' => null,
            'og_type' => 'website',
            'og_url' => null,
            'og_image' => null,
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     meta_description: string|null,
     *     meta_keywords: string,
     *     canonical_url: string|null,
     *     og_title: string,
     *     og_description: string|null,
     *     og_type: string,
     *     og_url: string|null,
     *     og_image: string|null
     * }
     */
    public function fromSlug(string $slug): array
    {
        $page = Page::query()->where('slug', $slug)->first();

        if ($page === null) {
            return $this->defaults();
        }

        return $this->fromPage($page);
    }

    /**
     * @return array{
     *     title: string,
     *     meta_description: string|null,
     *     meta_keywords: string,
     *     canonical_url: string|null,
     *     og_title: string,
     *     og_description: string|null,
     *     og_type: string,
     *     og_url: string|null,
     *     og_image: string|null
     * }
     */
    public function fromPage(Page $page): array
    {
        $defaults = $this->defaults();
        /** @var array<string, mixed> $og */
        $og = $page->og_tags ?? [];

        $title = trim((string) ($page->meta_title ?? ''));
        if ($title === '') {
            $title = $defaults['title'];
        }

        $keywords = $page->meta_keywords ?? [];
        $keywordsCsv = is_array($keywords)
            ? implode(', ', array_map(static fn ($v): string => trim((string) $v), $keywords))
            : '';

        $metaDescription = trim((string) ($page->meta_description ?? ''));

        $ogTitle = trim((string) ($og['title'] ?? ''));
        if ($ogTitle === '') {
            $ogTitle = $title;
        }

        $ogDescription = trim((string) ($og['description'] ?? ''));
        if ($ogDescription === '' && $metaDescription !== '') {
            $ogDescription = $metaDescription;
        }
        $ogType = trim((string) ($og['type'] ?? 'website'));
        if ($ogType === '') {
            $ogType = 'website';
        }

        return [
            'title' => $title,
            'meta_description' => $metaDescription !== '' ? $metaDescription : null,
            'meta_keywords' => $keywordsCsv,
            'canonical_url' => null,
            'og_title' => $ogTitle,
            'og_description' => $ogDescription !== '' ? $ogDescription : null,
            'og_type' => $ogType,
            'og_url' => $this->normalizePublicUrl($og['url'] ?? null),
            'og_image' => $this->absoluteAssetUrl(isset($og['image']) ? (string) $og['image'] : null),
        ];
    }

    /**
     * @param  array<string, mixed>  $package
     * @return array<string, string|null>
     */
    public function forTravelPackage(array $package, string $routeName): array
    {
        $slug = (string) ($package['slug'] ?? '');
        $title = (string) ($package['title'] ?? '');
        $description = Str::limit(strip_tags((string) ($package['description'] ?? '')), 320);
        $imagePath = (string) ($package['image'] ?? '');
        $canonical = $slug !== '' ? route($routeName, $slug) : null;

        return [
            'title' => trim($title.' | Poema Tours'),
            'meta_description' => $description !== '' ? $description : null,
            'meta_keywords' => '',
            'canonical_url' => $canonical,
            'og_title' => $title !== '' ? $title : 'Poema Tours',
            'og_description' => $description !== '' ? $description : null,
            'og_type' => 'website',
            'og_url' => $canonical,
            'og_image' => $this->absoluteAssetUrl($imagePath !== '' ? $imagePath : null),
        ];
    }

    /**
     * @param  array<string, mixed>  $blog
     * @return array<string, string|null>
     */
    public function forJourneyBlogPost(array $blog): array
    {
        $title = (string) ($blog['title'] ?? '');
        $excerpt = Str::limit(strip_tags((string) ($blog['excerpt'] ?? '')), 320);
        $slug = (string) ($blog['slug'] ?? '');
        $cover = $blog['cover_image'] ?? null;
        $canonical = $slug !== '' ? route('our.journeys.show', $slug) : null;

        return [
            'title' => trim($title.' | Poema Tours'),
            'meta_description' => $excerpt !== '' ? $excerpt : null,
            'meta_keywords' => '',
            'canonical_url' => $canonical,
            'og_title' => $title !== '' ? $title : 'Our Journeys',
            'og_description' => $excerpt !== '' ? $excerpt : null,
            'og_type' => 'article',
            'og_url' => $canonical,
            'og_image' => $this->resolvePossibleAbsoluteUrl(is_string($cover) ? $cover : null),
        ];
    }

    public function absoluteAssetUrl(?string $path): ?string
    {
        return $this->resolvePossibleAbsoluteUrl($path);
    }

    private function resolvePossibleAbsoluteUrl(?string $path): ?string
    {
        if ($path === null) {
            return null;
        }

        $path = trim($path);
        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, '//')) {
            return request()->getScheme().':'.$path;
        }

        return asset(ltrim($path, '/'));
    }

    private function normalizePublicUrl(mixed $url): ?string
    {
        if (! is_string($url)) {
            return null;
        }

        $url = trim($url);
        if ($url === '') {
            return null;
        }

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return url($url);
    }
}
