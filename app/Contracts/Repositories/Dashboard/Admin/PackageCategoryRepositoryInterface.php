<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\PackageCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface PackageCategoryRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?PackageCategory;

    /**
     * @param  array{slug: string, name: array<string, string>, title: array<string, string>, description: array<string, string>}  $data
     */
    public function create(array $data, ?UploadedFile $image, int $userId): PackageCategory;

    /**
     * @param  array{slug: string, name: array<string, string>, title: array<string, string>, description: array<string, string>}  $data
     */
    public function update(PackageCategory $packageCategory, array $data, ?UploadedFile $image, int $userId): PackageCategory;

    public function delete(PackageCategory $packageCategory, int $userId): void;
}
