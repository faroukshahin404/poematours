<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\BlogCategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreBlogCategoryRequest;
use App\Http\Requests\Dashboard\Admin\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogCategoryController extends Controller
{
    public function __construct(
        private readonly BlogCategoryRepositoryInterface $blogCategories
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/BlogCategories/Index', [
            'blogCategories' => $this->blogCategories->paginate(15)->through(function (BlogCategory $blogCategory) {
                return [
                    'id' => $blogCategory->id,
                    'slug' => $blogCategory->slug,
                    'name' => $blogCategory->labelForDefaultLanguage(),
                    'blogs_count' => $blogCategory->blogs_count,
                    'creator' => $blogCategory->creator?->name,
                    'updater' => $blogCategory->updater?->name,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/BlogCategories/Create');
    }

    public function store(StoreBlogCategoryRequest $request): RedirectResponse
    {
        $this->blogCategories->create($request->categoryPayload(), (int) $request->user()->id);

        return redirect()->route('admin.blog-categories.index')->with('status', __('Blog category created successfully.'));
    }

    public function edit(BlogCategory $blogCategory): Response
    {
        return Inertia::render('Dashboard/Admin/BlogCategories/Edit', [
            'blogCategory' => [
                'id' => $blogCategory->id,
                'slug' => $blogCategory->slug,
                'name_translations' => $blogCategory->nameTranslations(),
            ],
        ]);
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory): RedirectResponse
    {
        $this->blogCategories->update($blogCategory, $request->categoryPayload(), (int) $request->user()->id);

        return redirect()->route('admin.blog-categories.index')->with('status', __('Blog category updated successfully.'));
    }

    public function destroy(Request $request, BlogCategory $blogCategory): RedirectResponse
    {
        $this->blogCategories->delete($blogCategory, (int) $request->user()->id);

        return redirect()->route('admin.blog-categories.index')->with('status', __('Blog category deleted successfully.'));
    }
}
