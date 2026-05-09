<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\BlogRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreBlogRequest;
use App\Http\Requests\Dashboard\Admin\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function __construct(
        private readonly BlogRepositoryInterface $blogs
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Blogs/Index', [
            'blogs' => $this->blogs->paginate(15)->through(function (Blog $blog) {
                return [
                    'id' => $blog->id,
                    'slug' => $blog->slug,
                    'title' => $blog->titleForDefaultLanguage(),
                    'details' => Str::limit($blog->detailsForDefaultLanguage(), 90),
                    'category' => $blog->category?->labelForDefaultLanguage(),
                    'is_featured' => (bool) $blog->is_featured,
                    'views_count' => (int) $blog->views_count,
                    'images' => $blog->media->map(fn ($media) => ['id' => $media->id, 'url' => $media->publicUrl()])->values(),
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Blogs/Create', [
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function store(StoreBlogRequest $request): RedirectResponse
    {
        $this->blogs->create(
            $request->blogPayload(),
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()->route('admin.blogs.index')->with('status', __('Blog created successfully.'));
    }

    public function edit(Blog $blog): Response
    {
        $blog->load(['media:id,path,model_type,model_id']);

        return Inertia::render('Dashboard/Admin/Blogs/Edit', [
            'blog' => [
                'id' => $blog->id,
                'slug' => $blog->slug,
                'blog_category_id' => $blog->blog_category_id,
                'title_translations' => $blog->titleTranslations(),
                'details_translations' => $blog->detailsTranslations(),
                'is_featured' => (bool) $blog->is_featured,
                'views_count' => (int) $blog->views_count,
                'images' => $blog->media->map(fn ($media) => ['id' => $media->id, 'url' => $media->publicUrl()])->values(),
            ],
            'categories' => $this->categoryOptions(),
        ]);
    }

    public function update(UpdateBlogRequest $request, Blog $blog): RedirectResponse
    {
        $this->blogs->update(
            $blog,
            $request->blogPayload(),
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()->route('admin.blogs.index')->with('status', __('Blog updated successfully.'));
    }

    public function destroy(Request $request, Blog $blog): RedirectResponse
    {
        $this->blogs->delete($blog, (int) $request->user()->id);

        return redirect()->route('admin.blogs.index')->with('status', __('Blog deleted successfully.'));
    }

    /**
     * @return array<int, array{id: int, slug: string, label: string}>
     */
    private function categoryOptions(): array
    {
        return BlogCategory::query()
            ->orderBy('slug')
            ->get(['id', 'slug', 'name'])
            ->map(fn (BlogCategory $blogCategory) => [
                'id' => $blogCategory->id,
                'slug' => $blogCategory->slug,
                'label' => $blogCategory->labelForDefaultLanguage(),
            ])
            ->values()
            ->all();
    }
}
