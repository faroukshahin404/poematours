<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\PackageInclusionRepositoryInterface;
use App\Models\PackageInclusion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PackageInclusionRepository implements PackageInclusionRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return PackageInclusion::query()
            ->with(['creator:id,name'])
            ->withCount('packages')
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function create(array $data, int $userId): PackageInclusion
    {
        return DB::transaction(function () use ($data, $userId): PackageInclusion {
            $row = new PackageInclusion;
            $row->setAttribute('name', $data['name']);
            $row->icon = $data['icon'] ?? null;
            $row->created_by = $userId;
            $row->save();

            return $row->fresh(['creator:id,name']);
        });
    }

    public function update(PackageInclusion $packageInclusion, array $data, int $userId): PackageInclusion
    {
        return DB::transaction(function () use ($packageInclusion, $data, $userId): PackageInclusion {
            $packageInclusion->setAttribute('name', $data['name']);
            $packageInclusion->icon = $data['icon'] ?? null;
            $packageInclusion->updated_by = $userId;
            $packageInclusion->save();

            return $packageInclusion->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    public function delete(PackageInclusion $packageInclusion): void
    {
        DB::transaction(function () use ($packageInclusion): void {
            $packageInclusion->delete();
        });
    }
}
