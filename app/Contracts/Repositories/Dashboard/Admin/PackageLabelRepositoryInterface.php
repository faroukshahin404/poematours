<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\PackageLabel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PackageLabelRepositoryInterface
{
    public function paginate(?int $packageLabelGroupId, int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?PackageLabel;

    /**
     * @param  array{slug: string, name: array<string, string>, package_label_group_id: int}  $data
     */
    public function create(array $data, int $userId): PackageLabel;

    /**
     * @param  array{slug: string, name: array<string, string>, package_label_group_id: int}  $data
     */
    public function update(PackageLabel $label, array $data, int $userId): PackageLabel;

    public function delete(PackageLabel $label, int $userId): void;
}
