<?php

namespace App\Models;

use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

#[Fillable(['blog_category_id', 'title', 'details', 'slug', 'is_featured', 'views_count', 'created_by', 'updated_by'])]
class Blog extends Model
{
    use HasJsonLocalizedName;

    /**
     * @return array<string, string>
     */
    public static function decodeDetailsJson(mixed $value): array
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
     * @param  array<string, string>|string|null  $value
     */
    public function setTitleAttribute(mixed $value): void
    {
        if (is_array($value)) {
            $this->attributes['title'] = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['title'] = $value;
        }
    }

    /**
     * @param  array<string, string>|string|null  $value
     */
    public function setDetailsAttribute(mixed $value): void
    {
        if (is_array($value)) {
            $this->attributes['details'] = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['details'] = $value;
        }
    }

    /**
     * @return array<string, string>
     */
    public function titleTranslations(): array
    {
        return static::decodeNameJson($this->getAttributes()['title'] ?? null);
    }

    /**
     * @return array<string, string>
     */
    public function detailsTranslations(): array
    {
        return static::decodeDetailsJson($this->getAttributes()['details'] ?? null);
    }

    public function titleForDefaultLanguage(): string
    {
        $translations = $this->titleTranslations();
        $default = Language::defaultSlug();

        return (string) ($translations[$default] ?? reset($translations) ?: '');
    }

    public function detailsForDefaultLanguage(): string
    {
        $translations = $this->detailsTranslations();
        $default = Language::defaultSlug();

        return (string) ($translations[$default] ?? reset($translations) ?: '');
    }

    public static function generateUniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = trim(Str::slug($base));
        if ($slug === '') {
            $slug = 'blog';
        }

        $candidate = $slug;
        $suffix = 2;
        while (static::query()
            ->when($exceptId !== null, fn ($query) => $query->where('id', '!=', $exceptId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model', 'model_type', 'model_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
