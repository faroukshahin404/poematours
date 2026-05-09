<?php

namespace App\Repositories\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\BlogRepositoryInterface;
use App\Enums\StoragePath;
use App\Models\Blog;
use App\Models\Language;
use App\Services\Media\MediaUploadService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BlogRepository implements BlogRepositoryInterface
{
    public function __construct(
        private readonly MediaUploadService $mediaUploadService
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Blog::query()
            ->with(['category:id,name,slug', 'media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name'])
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function create(array $data, array $images, int $userId): Blog
    {
        return DB::transaction(function () use ($data, $images, $userId) {
            $slug = Blog::generateUniqueSlug($this->defaultTitle($data['title']));

            $blog = new Blog;
            $blog->blog_category_id = $data['blog_category_id'];
            $blog->title = $data['title'];
            $blog->details = $data['details'];
            $blog->slug = $slug;
            $blog->is_featured = $data['is_featured'];
            $blog->views_count = $data['views_count'];
            $blog->created_by = $userId;
            $blog->save();

            $this->mediaUploadService->uploadForModel($blog, StoragePath::Blogs, $images, $userId);

            return $blog->fresh(['category:id,name,slug', 'media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name']);
        });
    }

    public function update(Blog $blog, array $data, array $images, int $userId): Blog
    {
        return DB::transaction(function () use ($blog, $data, $images, $userId) {
            $slug = Blog::generateUniqueSlug($this->defaultTitle($data['title']), $blog->id);

            $this->mediaUploadService->deleteByIdsForModel($blog, $data['remove_media_ids']);
            $this->mediaUploadService->uploadForModel($blog, StoragePath::Blogs, $images, $userId);

            $blog->blog_category_id = $data['blog_category_id'];
            $blog->title = $data['title'];
            $blog->details = $data['details'];
            $blog->slug = $slug;
            $blog->is_featured = $data['is_featured'];
            $blog->views_count = $data['views_count'];
            $blog->updated_by = $userId;
            $blog->save();

            return $blog->fresh(['category:id,name,slug', 'media:id,path,model_type,model_id', 'creator:id,name', 'updater:id,name']);
        });
    }

    public function delete(Blog $blog, int $userId): void
    {
        DB::transaction(function () use ($blog): void {
            $this->mediaUploadService->deleteAllForModel($blog);
            $blog->delete();
        });
    }

    /**
     * @param  array<string, string>  $translations
     */
    private function defaultTitle(array $translations): string
    {
        $default = Language::defaultSlug();
        $title = trim((string) ($translations[$default] ?? ''));

        if ($title !== '') {
            return $title;
        }

        return trim((string) (reset($translations) ?: 'blog'));
    }
}
