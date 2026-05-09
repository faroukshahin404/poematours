<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\PackageInclusion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PackageInclusionRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array{name: array<string, string>, icon: ?string}  $data
     */
    public function create(array $data, int $userId): PackageInclusion;

    /**
     * @param  array{name: array<string, string>, icon: ?string}  $data
     */
    public function update(PackageInclusion $packageInclusion, array $data, int $userId): PackageInclusion;

    public function delete(PackageInclusion $packageInclusion): void;
}
