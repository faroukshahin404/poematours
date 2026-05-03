<?php

namespace App\Models;

use App\Enums\StoragePath;
use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['name', 'description', 'slug', 'destination_id', 'image', 'created_by', 'updated_by'])]
class Activity extends Model
{
    use HasJsonLocalizedName;

    public static function generateUniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = trim(Str::slug($base));
        if ($slug === '') {
            $slug = 'activity';
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

    public function imagePublicUrl(): ?string
    {
        $path = $this->attributes['image'] ?? null;
        if (! $path) {
            return null;
        }

        return asset('storage/'.$path);
    }

    public static function storeImage(UploadedFile $file): string
    {
        return $file->store(StoragePath::Activities->folder(), 'public');
    }

    public static function deleteStoredImage(?string $path): void
    {
        if (! $path) {
            return;
        }
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(TravelPackage::class, 'activity_package', 'activity_id', 'package_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destination_id');
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
