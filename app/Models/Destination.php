<?php

namespace App\Models;

use App\Enums\StoragePath;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'image', 'lat', 'lng', 'created_by', 'updated_by'])]
class Destination extends Model
{
    /**
     * Build a unique slug.
     */
    public static function generateUniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = trim(Str::slug($base));
        if ($slug === '') {
            $slug = 'destination';
        }

        $candidate = $slug;
        $suffix = 2;
        while (static::query()
            ->when($exceptId !== null, fn ($q) => $q->where('id', '!=', $exceptId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }

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

    public function imagePublicUrl(): ?string
    {
        $path = $this->attributes['image'] ?? null;
        if ($path === null || $path === '') {
            return null;
        }

        return asset('storage/'.$path);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function storeImage(UploadedFile $file): string
    {
        return $file->store(StoragePath::Destinations->folder(), 'public');
    }

    public static function deleteStoredImage(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
