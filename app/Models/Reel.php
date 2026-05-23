<?php

namespace App\Models;

use App\Enums\StoragePath;
use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'description', 'video_url', 'snapshot_url', 'created_by', 'updated_by'])]
class Reel extends Model
{
    use HasJsonLocalizedName;

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

            return;
        }

        $this->attributes['description'] = $value;
    }

    /**
     * @return array<string, string>
     */
    public function descriptionTranslations(): array
    {
        return static::decodeDescriptionJson($this->getAttributes()['description'] ?? null);
    }

    public function videoPublicUrl(): ?string
    {
        return self::publicUrlForStoredPath($this->getRawOriginal('video_url'));
    }

    public function snapshotPublicUrl(): ?string
    {
        return self::publicUrlForStoredPath($this->getRawOriginal('snapshot_url'));
    }

    public static function publicUrlForStoredPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return asset('storage/'.$path);
    }

    public static function storeVideo(UploadedFile $file): string
    {
        return $file->store(StoragePath::Reels->folder(), 'public');
    }

    public static function storeSnapshot(UploadedFile $file): string
    {
        return $file->store(StoragePath::Reels->folder().'/snapshots', 'public');
    }

    public static function deleteStoredVideo(?string $path): void
    {
        self::deleteStoredFile($path);
    }

    public static function deleteStoredSnapshot(?string $path): void
    {
        self::deleteStoredFile($path);
    }

    public static function deleteStoredFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
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
