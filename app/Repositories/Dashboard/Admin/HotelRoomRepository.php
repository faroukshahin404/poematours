<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\HotelRoomRepositoryInterface;
use App\Enums\StoragePath;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Services\Media\MediaUploadService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class HotelRoomRepository implements HotelRoomRepositoryInterface
{
    public function __construct(
        private readonly MediaUploadService $mediaUploadService
    ) {}

    /**
     * {@inheritdoc}
     */
    public function paginateByHotel(Hotel $hotel, int $perPage = 15): LengthAwarePaginator
    {
        return HotelRoom::query()
            ->where('hotel_id', $hotel->id)
            ->with(['media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name'])
            ->orderBy('slug')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?HotelRoom
    {
        return HotelRoom::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, array $images, int $userId): HotelRoom
    {
        return DB::transaction(function () use ($data, $images, $userId) {
            $hotelId = $data['hotel_id'];
            $slug = HotelRoom::generateUniqueSlug($data['slug'], $hotelId);

            $room = new HotelRoom;
            $room->name = $data['name'];
            $room->slug = $slug;
            $room->hotel_id = $hotelId;
            $room->capacity = $data['capacity'];
            $room->area = $data['area'];
            $room->base_price = $data['base_price'];
            $room->single_supplement = $data['single_supplement'];
            $room->created_by = $userId;
            $room->save();

            $this->mediaUploadService->uploadForModel($room, StoragePath::HotelRooms, $images, $userId);

            return $room->fresh(['media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(HotelRoom $room, array $data, array $images, int $userId): HotelRoom
    {
        return DB::transaction(function () use ($room, $data, $images, $userId) {
            $hotelId = $data['hotel_id'];
            $slug = HotelRoom::generateUniqueSlug($data['slug'], $hotelId, $room->id);

            $this->mediaUploadService->deleteByIdsForModel($room, $data['remove_media_ids']);
            $this->mediaUploadService->uploadForModel($room, StoragePath::HotelRooms, $images, $userId);

            $room->name = $data['name'];
            $room->slug = $slug;
            $room->hotel_id = $hotelId;
            $room->capacity = $data['capacity'];
            $room->area = $data['area'];
            $room->base_price = $data['base_price'];
            $room->single_supplement = $data['single_supplement'];
            $room->updated_by = $userId;
            $room->save();

            return $room->fresh(['media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(HotelRoom $room, int $userId): void
    {
        DB::transaction(function () use ($room): void {
            $this->mediaUploadService->deleteAllForModel($room);
            $room->delete();
        });
    }
}
