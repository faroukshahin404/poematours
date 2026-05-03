<?php

namespace App\Models;

use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'destination_id', 'stars', 'description', 'created_by', 'updated_by'])]
class Hotel extends Model
{
    use HasJsonLocalizedName;

    /**
     * Build a unique slug.
     */
    public static function generateUniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = trim(Str::slug($base));
        if ($slug === '') {
            $slug = 'hotel';
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
    public static function decodeDescriptionJson(mixed $value): array
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
    public function setDescriptionAttribute(mixed $value): void
    {
        if (is_array($value)) {
            $this->attributes['description'] = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['description'] = $value;
        }
    }

    /**
     * @return array<string, string>
     */
    public function descriptionTranslations(): array
    {
        return static::decodeDescriptionJson($this->getAttributes()['description'] ?? null);
    }

    public function descriptionForDefaultLanguage(): string
    {
        $translations = $this->descriptionTranslations();
        $default = Language::defaultSlug();

        return (string) ($translations[$default] ?? reset($translations) ?: '');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(HotelRoom::class, 'hotel_id');
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
