<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\BlogCategoryRepositoryInterface;
use App\Models\BlogCategory;
use App\Models\Language;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BlogCategoryRepository implements BlogCategoryRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return BlogCategory::query()
            ->withCount('blogs')
            ->with(['creator:id,name', 'updater:id,name'])
            ->orderBy('slug')
            ->paginate($perPage);
    }

    public function create(array $data, int $userId): BlogCategory
    {
        return DB::transaction(function () use ($data, $userId) {
            $slug = BlogCategory::generateUniqueSlug($this->defaultName($data['name']));

            $category = new BlogCategory;
            $category->name = $data['name'];
            $category->slug = $slug;
            $category->created_by = $userId;
            $category->save();

            return $category->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    public function update(BlogCategory $blogCategory, array $data, int $userId): BlogCategory
    {
        return DB::transaction(function () use ($blogCategory, $data, $userId) {
            $slug = BlogCategory::generateUniqueSlug($this->defaultName($data['name']), $blogCategory->id);

            $blogCategory->name = $data['name'];
            $blogCategory->slug = $slug;
            $blogCategory->updated_by = $userId;
            $blogCategory->save();

            return $blogCategory->fresh(['creator:id,name', 'updater:id,name']);
        });
    }

    public function delete(BlogCategory $blogCategory, int $userId): void
    {
        DB::transaction(function () use ($blogCategory): void {
            $blogCategory->delete();
        });
    }

    /**
     * @param  array<string, string>  $translations
     */
    private function defaultName(array $translations): string
    {
        $default = Language::defaultSlug();
        $name = trim((string) ($translations[$default] ?? ''));

        if ($name !== '') {
            return $name;
        }

        return trim((string) (reset($translations) ?: 'blog-category'));
    }
}
