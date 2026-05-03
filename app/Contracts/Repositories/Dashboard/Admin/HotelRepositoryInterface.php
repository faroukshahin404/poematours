<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Hotel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface HotelRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Hotel;

    /**
     * @param  array{slug: string, name: array<string, string>, destination_id: int, stars: int, description: array<string, string>}  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function create(array $data, array $images, int $userId): Hotel;

    /**
     * @param  array{slug: string, name: array<string, string>, destination_id: int, stars: int, description: array<string, string>, remove_media_ids: array<int, int>}  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function update(Hotel $hotel, array $data, array $images, int $userId): Hotel;

    public function delete(Hotel $hotel, int $userId): void;
}
