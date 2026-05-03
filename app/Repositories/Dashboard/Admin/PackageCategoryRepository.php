<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\PackageCategoryRepositoryInterface;
use App\Models\PackageCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PackageCategoryRepository implements PackageCategoryRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return PackageCategory::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->orderBy('slug')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?PackageCategory
    {
        return PackageCategory::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, ?UploadedFile $image, int $userId): PackageCategory
    {
        return DB::transaction(function () use ($data, $image, $userId) {
            $slug = PackageCategory::generateUniqueSlug($data['slug']);
            $imagePath = $image !== null ? PackageCategory::storeImage($image) : null;

            $category = new PackageCategory;
            $category->setAttribute('name', $data['name']);
            $category->setAttribute('title', $data['title']);
            $category->setAttribute('description', $data['description']);
            $category->setAttribute('slug', $slug);
            $category->setAttribute('image', $imagePath);
            $category->setAttribute('created_by', $userId);
            $category->save();

            return $category->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(PackageCategory $packageCategory, array $data, ?UploadedFile $image, int $userId): PackageCategory
    {
        return DB::transaction(function () use ($packageCategory, $data, $image, $userId) {
            $slug = PackageCategory::generateUniqueSlug($data['slug'], $packageCategory->id);

            if ($image !== null) {
                PackageCategory::deleteStoredImage($packageCategory->getRawOriginal('image'));
                $packageCategory->setAttribute('image', PackageCategory::storeImage($image));
            }

            $packageCategory->setAttribute('name', $data['name']);
            $packageCategory->setAttribute('title', $data['title']);
            $packageCategory->setAttribute('description', $data['description']);
            $packageCategory->setAttribute('slug', $slug);
            $packageCategory->setAttribute('updated_by', $userId);
            $packageCategory->save();

            return $packageCategory->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(PackageCategory $packageCategory, int $userId): void
    {
        DB::transaction(function () use ($packageCategory): void {
            PackageCategory::deleteStoredImage($packageCategory->getRawOriginal('image'));
            $packageCategory->delete();
        });
    }
}
