<?php

namespace App\Models;

use App\Enums\StoragePath;
use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'slug',
    'boat',
    'boat_image',
    'suite',
    'suite_image',
    'food',
    'food_image',
    'wellness',
    'wellness_image',
    'created_by',
    'updated_by',
])]
class Boat extends Model
{
    use HasJsonLocalizedName;

    /**
     * Build a unique slug.
     */
    public static function generateUniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = trim(Str::slug($base));
        if ($slug === '') {
            $slug = 'boat';
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
    public static function decodeJsonField(mixed $value): array
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
    public function setBoatAttribute(mixed $value): void
    {
        $this->attributes['boat'] = is_array($value)
            ? json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
            : $value;
    }

    /**
     * @param  array<string, string>|string|null  $value
     */
    public function setSuiteAttribute(mixed $value): void
    {
        $this->attributes['suite'] = is_array($value)
            ? json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
            : $value;
    }

    /**
     * @param  array<string, string>|string|null  $value
     */
    public function setFoodAttribute(mixed $value): void
    {
        $this->attributes['food'] = is_array($value)
            ? json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
            : $value;
    }

    /**
     * @param  array<string, string>|string|null  $value
     */
    public function setWellnessAttribute(mixed $value): void
    {
        $this->attributes['wellness'] = is_array($value)
            ? json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
            : $value;
    }

    /**
     * @return array<string, string>
     */
    public function boatTranslations(): array
    {
        return static::decodeJsonField($this->getAttributes()['boat'] ?? null);
    }

    /**
     * @return array<string, string>
     */
    public function suiteTranslations(): array
    {
        return static::decodeJsonField($this->getAttributes()['suite'] ?? null);
    }

    /**
     * @return array<string, string>
     */
    public function foodTranslations(): array
    {
        return static::decodeJsonField($this->getAttributes()['food'] ?? null);
    }

    /**
     * @return array<string, string>
     */
    public function wellnessTranslations(): array
    {
        return static::decodeJsonField($this->getAttributes()['wellness'] ?? null);
    }

    public static function storeSectionImage(UploadedFile $file, string $section): string
    {
        return $file->store(StoragePath::Boats->folder().'/'.$section, 'public');
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

    public function sectionImageUrl(string $field): ?string
    {
        $path = $this->attributes[$field] ?? null;

        if ($path === null || $path === '') {
            return null;
        }

        return asset('storage/'.$path);
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
