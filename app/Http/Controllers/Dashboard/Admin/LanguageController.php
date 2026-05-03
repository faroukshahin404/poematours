<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\LanguageRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreLanguageRequest;
use App\Http\Requests\Dashboard\Admin\UpdateLanguageRequest;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LanguageController extends Controller
{
    public function __construct(
        private readonly LanguageRepositoryInterface $languages
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Languages/Index', [
            'languages' => $this->languages->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Languages/Create', [
            'languageCount' => Language::query()->count(),
        ]);
    }

    public function store(StoreLanguageRequest $request): RedirectResponse
    {
        $this->languages->create(
            $request->languagePayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.languages.index')
            ->with('status', __('Language created successfully.'));
    }

    public function edit(Language $language): Response
    {
        return Inertia::render('Dashboard/Admin/Languages/Edit', [
            'language' => [
                'id' => $language->id,
                'name' => $language->name,
                'slug' => $language->slug,
                'is_default' => $language->is_default,
            ],
            'languageCount' => Language::query()->count(),
        ]);
    }

    public function update(UpdateLanguageRequest $request, Language $language): RedirectResponse
    {
        $this->languages->update(
            $language,
            $request->languagePayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.languages.index')
            ->with('status', __('Language updated successfully.'));
    }

    public function destroy(Request $request, Language $language): RedirectResponse
    {
        $this->languages->delete($language, (int) $request->user()->id);

        return redirect()
            ->route('admin.languages.index')
            ->with('status', __('Language deleted successfully.'));
    }
}
