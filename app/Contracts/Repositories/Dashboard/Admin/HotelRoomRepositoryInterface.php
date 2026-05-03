<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface HotelRoomRepositoryInterface
{
    public function paginateByHotel(Hotel $hotel, int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?HotelRoom;

    /**
     * @param  array{slug: string, hotel_id: int, name: array<string, string>, capacity: int, area: float|null, base_price: float, single_supplement: float}  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function create(array $data, array $images, int $userId): HotelRoom;

    /**
     * @param  array{slug: string, hotel_id: int, name: array<string, string>, capacity: int, area: float|null, base_price: float, single_supplement: float, remove_media_ids: array<int, int>}  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function update(HotelRoom $room, array $data, array $images, int $userId): HotelRoom;

    public function delete(HotelRoom $room, int $userId): void;
}
