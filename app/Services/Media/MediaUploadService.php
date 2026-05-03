<?php

namespace App\Services\Media;

use App\Enums\StoragePath;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaUploadService
{
    /**
     * @param  array<int, UploadedFile>  $files
     * @return Collection<int, Media>
     */
    public function uploadForModel(Model $model, StoragePath $storagePath, array $files, ?int $userId = null): Collection
    {
        if ($files === []) {
            return collect();
        }

        return DB::transaction(function () use ($model, $storagePath, $files, $userId) {
            $created = [];
            foreach ($files as $file) {
                $path = $file->store($storagePath->folder(), 'public');
                $created[] = $model->media()->create([
                    'path' => $path,
                    'storage_path' => $storagePath->value,
                    'created_by' => $userId,
                ]);
            }

            return collect($created);
        });
    }

    /**
     * @param  array<int, int>  $mediaIds
     */
    public function deleteByIdsForModel(Model $model, array $mediaIds): void
    {
        if ($mediaIds === []) {
            return;
        }

        DB::transaction(function () use ($model, $mediaIds): void {
            $mediaItems = $model->media()->whereIn('id', $mediaIds)->get(['id', 'path']);
            foreach ($mediaItems as $media) {
                if ($media->path !== '' && Storage::disk('public')->exists($media->path)) {
                    Storage::disk('public')->delete($media->path);
                }
                $media->delete();
            }
        });
    }

    public function deleteAllForModel(Model $model): void
    {
        DB::transaction(function () use ($model): void {
            $mediaItems = $model->media()->get(['id', 'path']);
            foreach ($mediaItems as $media) {
                if ($media->path !== '' && Storage::disk('public')->exists($media->path)) {
                    Storage::disk('public')->delete($media->path);
                }
                $media->delete();
            }
        });
    }
}
