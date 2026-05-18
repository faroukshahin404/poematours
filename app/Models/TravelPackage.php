<?php

namespace App\Models;

use App\Enums\StoragePath;
use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

#[Fillable(['title', 'description', 'details', 'slug', 'pdf', 'created_by', 'updated_by'])]
class TravelPackage extends Model
{
    use HasJsonLocalizedName;

    protected $table = 'packages';

    public function getNameAttribute(mixed $value): string
    {
        return $this->getTitleAttribute($value);
    }

    /**
     * @return array<string, string>
     */
    public static function decodeTitleJson(mixed $value): array
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

    public function getTitleAttribute(mixed $value): string
    {
        return $this->getNameAttributeFromTranslations(static::decodeTitleJson($value));
    }

    private function getNameAttributeFromTranslations(array $translations): string
    {
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
    public function titleTranslations(): array
    {
        return static::decodeTitleJson($this->getAttributes()['title'] ?? null);
    }

    /**
     * @return array<string, string>
     */
    public function descriptionTranslations(): array
    {
        return static::decodeNameJson($this->getAttributes()['description'] ?? null);
    }

    /**
     * @return array<string, mixed>
     */
    public function detailsData(): array
    {
        $value = $this->getAttributes()['details'] ?? null;
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
     * @param  array<string, mixed>|string|null  $value
     */
    public function setDetailsAttribute(mixed $value): void
    {
        if (is_array($value)) {
            $this->attributes['details'] = json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['details'] = $value;
        }
    }

    public static function generateUniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = trim(Str::slug($base));
        if ($slug === '') {
            $slug = 'package';
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

    public static function storePdf(UploadedFile $file): string
    {
        return static::storePublicFile($file, StoragePath::Packages->folder().'/pdf');
    }

    public static function storeMediaFile(UploadedFile $file): string
    {
        return static::storePublicFile($file, StoragePath::Packages->folder());
    }

    public static function deleteStoredPdf(?string $path): void
    {
        static::deleteStoredFile($path);
    }

    public static function deleteStoredFile(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        $fullPath = public_path('storage/'.$path);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    public function pdfPublicUrl(): ?string
    {
        $path = $this->attributes['pdf'] ?? null;
        if ($path === null || $path === '') {
            return null;
        }

        return asset('storage/'.$path);
    }

    private static function storePublicFile(UploadedFile $file, string $folder): string
    {
        $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $targetPath = public_path('storage/'.$folder);

        if (! is_dir($targetPath)) {
            mkdir($targetPath, 0775, true);
        }

        $file->move($targetPath, $fileName);

        return $folder.'/'.$fileName;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(PackageCategory::class, 'package_package_category', 'package_id', 'package_category_id');
    }

    public function labelGroups(): BelongsToMany
    {
        return $this->belongsToMany(PackageLabelGroup::class, 'package_package_label_group', 'package_id', 'package_label_group_id');
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(PackageLabel::class, 'package_package_label', 'package_id', 'package_label_id');
    }

    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_package', 'package_id', 'activity_id');
    }

    public function inclusions(): BelongsToMany
    {
        return $this->belongsToMany(
            PackageInclusion::class,
            'package_package_inclusion',
            'package_id',
            'package_inclusion_id'
        );
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(Itinerary::class, 'package_id');
    }

    public function datePrices(): HasMany
    {
        return $this->hasMany(PackageDatePrice::class, 'package_id');
    }

    public function packageReviews(): HasMany
    {
        return $this->hasMany(PackageReview::class, 'package_id');
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
