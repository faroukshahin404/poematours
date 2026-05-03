<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\PackageCategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StorePackageCategoryRequest;
use App\Http\Requests\Dashboard\Admin\UpdatePackageCategoryRequest;
use App\Models\Language;
use Illuminate\Support\Str;
use App\Models\PackageCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PackageCategoryController extends Controller
{
    public function __construct(
        private readonly PackageCategoryRepositoryInterface $packageCategories
    ) {}

    public function index(): Response
    {
        $locale = Language::defaultSlug();

        return Inertia::render('Dashboard/Admin/PackageCategories/Index', [
            'packageCategories' => $this->packageCategories->paginate(15)->through(function (PackageCategory $category) use ($locale) {
                $title = $this->translatedValue($category->titleTranslations(), $locale);
                $description = $this->translatedValue($category->descriptionTranslations(), $locale);

                return [
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'label' => $category->labelForDefaultLanguage(),
                    'title' => $title,
                    'description' => Str::limit($description, 90),
                    'image_url' => $category->imagePublicUrl(),
                    'creator' => $category->creator?->name,
                    'updater' => $category->updater?->name,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/PackageCategories/Create');
    }

    public function store(StorePackageCategoryRequest $request): RedirectResponse
    {
        $this->packageCategories->create(
            $request->categoryPayload(),
            $request->file('image'),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.package-categories.index')
            ->with('status', __('Package category created successfully.'));
    }

    public function edit(PackageCategory $packageCategory): Response
    {
        return Inertia::render('Dashboard/Admin/PackageCategories/Edit', [
            'packageCategory' => [
                'id' => $packageCategory->id,
                'slug' => $packageCategory->slug,
                'name_translations' => $packageCategory->nameTranslations(),
                'title_translations' => $packageCategory->titleTranslations(),
                'description_translations' => $packageCategory->descriptionTranslations(),
                'image_url' => $packageCategory->imagePublicUrl(),
            ],
        ]);
    }

    public function update(UpdatePackageCategoryRequest $request, PackageCategory $packageCategory): RedirectResponse
    {
        $this->packageCategories->update(
            $packageCategory,
            $request->categoryPayload(),
            $request->file('image'),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.package-categories.index')
            ->with('status', __('Package category updated successfully.'));
    }

    public function destroy(Request $request, PackageCategory $packageCategory): RedirectResponse
    {
        $this->packageCategories->delete($packageCategory, (int) $request->user()->id);

        return redirect()
            ->route('admin.package-categories.index')
            ->with('status', __('Package category deleted successfully.'));
    }

    /**
     * @param  array<string, string>  $translations
     */
    private function translatedValue(array $translations, string $locale): string
    {
        if (isset($translations[$locale]) && trim((string) $translations[$locale]) !== '') {
            return trim((string) $translations[$locale]);
        }

        $first = collect($translations)
            ->map(fn (mixed $value): string => trim((string) $value))
            ->first(fn (string $value): bool => $value !== '');

        return $first ?: '';
    }
}
