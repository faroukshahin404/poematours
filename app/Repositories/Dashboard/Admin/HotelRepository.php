<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\HotelRepositoryInterface;
use App\Enums\StoragePath;
use App\Models\Hotel;
use App\Services\Media\MediaUploadService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class HotelRepository implements HotelRepositoryInterface
{
    public function __construct(
        private readonly MediaUploadService $mediaUploadService
    ) {}

    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Hotel::query()
            ->with(['destination:id,name,slug', 'media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name'])
            ->withCount('rooms')
            ->orderBy('slug')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?Hotel
    {
        return Hotel::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, array $images, int $userId): Hotel
    {
        return DB::transaction(function () use ($data, $images, $userId) {
            $slug = Hotel::generateUniqueSlug($data['slug']);

            $hotel = new Hotel;
            $hotel->name = $data['name'];
            $hotel->slug = $slug;
            $hotel->destination_id = $data['destination_id'];
            $hotel->stars = $data['stars'];
            $hotel->description = $data['description'];
            $hotel->created_by = $userId;
            $hotel->save();

            $this->mediaUploadService->uploadForModel($hotel, StoragePath::Hotels, $images, $userId);

            return $hotel->fresh(['destination:id,name,slug', 'media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(Hotel $hotel, array $data, array $images, int $userId): Hotel
    {
        return DB::transaction(function () use ($hotel, $data, $images, $userId) {
            $slug = Hotel::generateUniqueSlug($data['slug'], $hotel->id);

            $this->mediaUploadService->deleteByIdsForModel($hotel, $data['remove_media_ids']);
            $this->mediaUploadService->uploadForModel($hotel, StoragePath::Hotels, $images, $userId);

            $hotel->name = $data['name'];
            $hotel->slug = $slug;
            $hotel->destination_id = $data['destination_id'];
            $hotel->stars = $data['stars'];
            $hotel->description = $data['description'];
            $hotel->updated_by = $userId;
            $hotel->save();

            return $hotel->fresh(['destination:id,name,slug', 'media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Hotel $hotel, int $userId): void
    {
        DB::transaction(function () use ($hotel): void {
            $hotel->load('rooms.media');
            foreach ($hotel->rooms as $room) {
                $this->mediaUploadService->deleteAllForModel($room);
            }
            $this->mediaUploadService->deleteAllForModel($hotel);
            $hotel->delete();
        });
    }
}
