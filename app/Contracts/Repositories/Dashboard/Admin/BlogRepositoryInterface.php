<?php

namespace App\Contracts\Repositories\Dashboard\Admin;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

interface BlogRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array{blog_category_id: int, title: array<string, string>, details: array<string, string>, is_featured: bool, views_count: int}  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function create(array $data, array $images, int $userId): Blog;

    /**
     * @param  array{blog_category_id: int, title: array<string, string>, details: array<string, string>, is_featured: bool, views_count: int, remove_media_ids: array<int, int>}  $data
     * @param  array<int, UploadedFile>  $images
     */
    public function update(Blog $blog, array $data, array $images, int $userId): Blog;

    public function delete(Blog $blog, int $userId): void;
}
