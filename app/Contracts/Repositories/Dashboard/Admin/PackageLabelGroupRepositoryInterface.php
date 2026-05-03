<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\PackageLabelGroup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PackageLabelGroupRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?PackageLabelGroup;

    /**
     * @param  array{slug: string, name: array<string, string>}  $data
     */
    public function create(array $data, int $userId): PackageLabelGroup;

    /**
     * @param  array{slug: string, name: array<string, string>}  $data
     */
    public function update(PackageLabelGroup $group, array $data, int $userId): PackageLabelGroup;

    public function delete(PackageLabelGroup $group, int $userId): void;
}
