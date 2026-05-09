<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\BlogCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BlogCategoryRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array{name: array<string, string>}  $data
     */
    public function create(array $data, int $userId): BlogCategory;

    /**
     * @param  array{name: array<string, string>}  $data
     */
    public function update(BlogCategory $blogCategory, array $data, int $userId): BlogCategory;

    public function delete(BlogCategory $blogCategory, int $userId): void;
}
