<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\PackageLabelRepositoryInterface;
use App\Models\PackageLabel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PackageLabelRepository implements PackageLabelRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function paginate(?int $packageLabelGroupId, int $perPage = 15): LengthAwarePaginator
    {
        $query = PackageLabel::query()
            ->with([
                'group:id,slug,name',
                'creator:id,name',
                'updater:id,name',
            ])
            ->orderBy('package_label_group_id')
            ->orderBy('slug');

        if ($packageLabelGroupId !== null && $packageLabelGroupId > 0) {
            $query->where('package_label_group_id', $packageLabelGroupId);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): ?PackageLabel
    {
        return PackageLabel::query()->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data, int $userId): PackageLabel
    {
        return DB::transaction(function () use ($data, $userId) {
            $groupId = $data['package_label_group_id'];
            $slug = PackageLabel::generateUniqueSlug($data['slug'], $groupId);

            $label = new PackageLabel;
            $label->name = $data['name'];
            $label->slug = $slug;
            $label->package_label_group_id = $groupId;
            $label->created_by = $userId;
            $label->save();

            return $label->fresh(['group:id,slug,name', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function update(PackageLabel $label, array $data, int $userId): PackageLabel
    {
        return DB::transaction(function () use ($label, $data, $userId) {
            $groupId = $data['package_label_group_id'];
            $slug = PackageLabel::generateUniqueSlug($data['slug'], $groupId, $label->id);

            $label->name = $data['name'];
            $label->slug = $slug;
            $label->package_label_group_id = $groupId;
            $label->updated_by = $userId;
            $label->save();

            return $label->fresh(['group:id,slug,name', 'creator:id,name', 'updater:id,name']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(PackageLabel $label, int $userId): void
    {
        DB::transaction(function () use ($label): void {
            $label->delete();
        });
    }
}
