<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Language;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LanguageRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Language;

    /**
     * @param  array{name: string, is_default?: bool}  $data
     */
    public function create(array $data, int $userId): Language;

    /**
     * @param  array{name: string, is_default?: bool}  $data
     */
    public function update(Language $language, array $data, int $userId): Language;

    public function delete(Language $language, int $userId): void;
}
