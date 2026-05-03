<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\UpdatePageContentRequest;
use App\Http\Requests\Dashboard\Admin\UpdatePageSectionContentRequest;
use App\Models\Language;
use App\Models\Page;
use App\Models\PageSection;
use App\Services\Dashboard\Admin\PageContentService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PageContentController extends Controller
{
    public function __construct(
        private readonly PageContentService $pageContentService
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Pages/Index', [
            'pages' => Page::query()
                ->withCount('sections')
                ->orderBy('name')
                ->get()
                ->map(fn (Page $page): array => [
                    'id' => $page->id,
                    'name' => $page->name,
                    'slug' => $page->slug,
                    'meta_title' => $page->meta_title,
                    'sections_count' => $page->sections_count,
                    'sections' => $page->sections()->ordered()->get(['id', 'key'])->map(
                        fn (PageSection $section): array => [
                            'id' => $section->id,
                            'key' => $section->key,
                        ]
                    )->values(),
                    'updated_at' => $page->updated_at?->toDateTimeString(),
                ])
                ->values(),
        ]);
    }

    public function edit(Page $page): Response
    {
        $sections = $page->sections()->ordered()->get(['id', 'key'])->map(
            fn (PageSection $section): array => [
                'id' => $section->id,
                'key' => $section->key,
            ]
        )->values();

        return Inertia::render('Dashboard/Admin/Pages/Edit', [
            'page' => [
                'id' => $page->id,
                'name' => $page->name,
                'slug' => $page->slug,
                'meta_title' => $page->meta_title,
                'meta_description' => $page->meta_description,
                'meta_keywords' => $page->meta_keywords ?? [],
                'og_tags' => $page->og_tags ?? [],
            ],
            'sections' => $sections,
        ]);
    }

    public function update(UpdatePageContentRequest $request, Page $page): RedirectResponse
    {
        $this->pageContentService->updatePageSeo($page, $request->pagePayload());

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', __('Page SEO updated successfully.'));
    }

    public function editSection(Page $page, PageSection $section): Response
    {
        abort_unless($section->page_id === $page->id, 404);

        return Inertia::render('Dashboard/Admin/Pages/Sections/Edit', [
            'page' => [
                'id' => $page->id,
                'name' => $page->name,
                'slug' => $page->slug,
            ],
            'section' => [
                'id' => $section->id,
                'key' => $section->key,
                'order' => $section->order,
                'is_active' => $section->is_active,
                'content' => $section->content ?? [],
            ],
            'sections' => $page->sections()->ordered()->get(['id', 'key'])->map(
                fn (PageSection $item): array => ['id' => $item->id, 'key' => $item->key]
            )->values(),
            'languages' => Language::query()->orderByDesc('is_default')->orderBy('name')->get(['name', 'slug'])->values(),
        ]);
    }

    public function updateSection(
        UpdatePageSectionContentRequest $request,
        Page $page,
        PageSection $section
    ): RedirectResponse {
        abort_unless($section->page_id === $page->id, 404);

        $this->pageContentService->updateSection($section, $request->sectionPayload($section));

        return redirect()
            ->route('admin.pages.sections.edit', ['page' => $page->id, 'section' => $section->id])
            ->with('status', __('Section content updated successfully.'));
    }
}
