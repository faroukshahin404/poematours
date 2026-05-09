<?php

namespace App\Services\Frontend;

use App\Models\Blog;
use App\Models\Language;
use Illuminate\Support\Str;

class JourneyBlogService
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return Blog::query()
            ->with(['category:id,name,slug', 'media:id,path,storage_path,model_type,model_id'])
            ->orderByDesc('is_featured')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Blog $blog): array => $this->transformBlog($blog))
            ->all();
    }

    public function findBySlug(string $slug): ?array
    {
        $blog = Blog::query()
            ->with(['category:id,name,slug', 'media:id,path,storage_path,model_type,model_id'])
            ->where('slug', $slug)
            ->first();

        if (! $blog) {
            return null;
        }

        return $this->transformBlog($blog);
    }

    /**
     * Random featured blogs for home sections (up to $limit rows).
     *
     * @return array<int, array<string, mixed>>
     */
    public function randomFeatured(int $limit = 3): array
    {
        return Blog::query()
            ->where('is_featured', true)
            ->with(['category:id,name,slug', 'media:id,path,storage_path,model_type,model_id'])
            ->inRandomOrder()
            ->limit($limit)
            ->get()
            ->map(fn (Blog $blog): array => $this->transformBlog($blog))
            ->all();
    }

    /**
     * @return array{slug: string, title: string, excerpt: string, category: string, cover_image: string, gallery: array<int, string>, content: array<int, string>, is_featured: bool, views_count: int}
     */
    private function transformBlog(Blog $blog): array
    {
        $details = $this->localizedValue($blog->detailsTranslations());
        $title = $this->localizedValue($blog->titleTranslations());
        $gallery = $blog->media
            ->map(fn ($media): string => $media->publicUrl())
            ->values()
            ->all();
        $coverImage = $gallery[0] ?? asset('assets/images/placeholders/banner.jpeg');
        $content = collect(preg_split('/\r\n|\r|\n/', $details) ?: [])
            ->map(fn (string $paragraph): string => trim($paragraph))
            ->filter(fn (string $paragraph): bool => $paragraph !== '')
            ->values()
            ->all();

        if ($content === []) {
            $content = [$details];
        }

        return [
            'slug' => $blog->slug,
            'title' => $title,
            'excerpt' => Str::limit(strip_tags($details), 220),
            'category' => $blog->category?->labelForDefaultLanguage() ?: 'General',
            'cover_image' => $coverImage,
            'gallery' => $gallery !== [] ? $gallery : [$coverImage],
            'content' => $content,
            'is_featured' => (bool) $blog->is_featured,
            'views_count' => (int) $blog->views_count,
        ];
    }

    /**
     * @param  array<string, string>  $translations
     */
    private function localizedValue(array $translations): string
    {
        $locale = (string) session('locale', Language::defaultSlug());

        if (isset($translations[$locale]) && trim((string) $translations[$locale]) !== '') {
            return trim((string) $translations[$locale]);
        }

        $default = Language::defaultSlug();
        if (isset($translations[$default]) && trim((string) $translations[$default]) !== '') {
            return trim((string) $translations[$default]);
        }

        return (string) collect($translations)
            ->map(fn (mixed $value): string => trim((string) $value))
            ->first(fn (string $value): bool => $value !== '');
    }
}
