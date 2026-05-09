<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Country;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CountryRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Country;

    /**
     * @param  array{name: string}  $data
     */
    public function create(array $data, int $userId): Country;

    /**
     * @param  array{name: string}  $data
     */
    public function update(Country $country, array $data, int $userId): Country;

    public function delete(Country $country): void;
}
