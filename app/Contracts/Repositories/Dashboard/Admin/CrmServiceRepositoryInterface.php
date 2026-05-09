<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\CrmService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CrmServiceRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array{name: string}  $data
     */
    public function create(array $data, int $userId): CrmService;

    /**
     * @param  array{name: string}  $data
     */
    public function update(CrmService $service, array $data, int $userId): CrmService;

    public function delete(CrmService $service): void;
}
