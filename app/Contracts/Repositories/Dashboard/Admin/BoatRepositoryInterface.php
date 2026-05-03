<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Boat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface BoatRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Boat;

    /**
     * @param  array{slug: string, name: array<string, string>, boat: array<string, string>, suite: array<string, string>, food: array<string, string>, wellness: array<string, string>}  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function create(array $data, ?UploadedFile $boatImage, ?UploadedFile $suiteImage, ?UploadedFile $foodImage, ?UploadedFile $wellnessImage, array $images, int $userId): Boat;

    /**
     * @param  array{slug: string, name: array<string, string>, boat: array<string, string>, suite: array<string, string>, food: array<string, string>, wellness: array<string, string>, remove_media_ids: array<int, int>, remove_boat_image: bool, remove_suite_image: bool, remove_food_image: bool, remove_wellness_image: bool}  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function update(Boat $boat, array $data, ?UploadedFile $boatImage, ?UploadedFile $suiteImage, ?UploadedFile $foodImage, ?UploadedFile $wellnessImage, array $images, int $userId): Boat;

    public function delete(Boat $boat, int $userId): void;
}
