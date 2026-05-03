<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\PackageLabelGroupRepositoryInterface;
use App\Models\PackageLabelGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PackageLabelGroupRepository implements PackageLabelGroupRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return PackageLabelGroup::query()
            ->with(['creator:id,name', 'updater:id,name'])
            ->withCount('labels')
            ->orderBy('slug')
            ->paginate($perPage);
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?PackageLabelGroup
    {
        return PackageLabelGroup::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, int $userId): PackageLabelGroup
    {
        return DB::transaction(function () use ($data, $userId) {
            $slug = PackageLabelGroup::generateUniqueSlug($data['slug']);

            $group = new PackageLabelGroup;
            $group->name = $data['name'];
            $group->slug = $slug;
            $group->created_by = $userId;
            $group->save();

            return $group->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(PackageLabelGroup $group, array $data, int $userId): PackageLabelGroup
    {
        return DB::transaction(function () use ($group, $data, $userId) {
            $slug = PackageLabelGroup::generateUniqueSlug($data['slug'], $group->id);

            $group->name = $data['name'];
            $group->slug = $slug;
            $group->updated_by = $userId;
            $group->save();

            return $group->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(PackageLabelGroup $group, int $userId): void
    {
        DB::transaction(function () use ($group): void {
            $group->delete();
        });
    }
}
