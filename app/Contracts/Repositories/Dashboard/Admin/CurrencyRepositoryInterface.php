<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Currency;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CurrencyRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Currency;

    /**
     * @param  array{name: string, is_default?: bool}  $data
     */
    public function create(array $data, int $userId): Currency;

    /**
     * @param  array{name: string, is_default?: bool}  $data
     */
    public function update(Currency $currency, array $data, int $userId): Currency;

    public function delete(Currency $currency, int $userId): void;
}
