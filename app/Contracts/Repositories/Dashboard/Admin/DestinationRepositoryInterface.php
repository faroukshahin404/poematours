<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Destination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface DestinationRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Destination;

    /**
     * @param  array{slug: string, name: array<string, string>, lat: float|null, lng: float|null}  $data
     */
    public function create(array $data, ?UploadedFile $image, int $userId): Destination;

    /**
     * @param  array{slug: string, name: array<string, string>, lat: float|null, lng: float|null}  $data
     */
    public function update(Destination $destination, array $data, ?UploadedFile $image, int $userId): Destination;

    public function delete(Destination $destination, int $userId): void;
}
