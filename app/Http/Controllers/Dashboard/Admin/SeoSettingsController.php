<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\UpdateSeoSettingsRequest;
use App\Services\Dashboard\Admin\SeoSettingsService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SeoSettingsController extends Controller
{
    public function __construct(
        private readonly SeoSettingsService $seoSettingsService
    ) {}

    public function edit(): Response
    {
        return Inertia::render('Dashboard/Admin/Settings/Seo', [
            'settings' => [
                'google_seo_script' => $this->seoSettingsService->googleSeoScriptForForm(),
            ],
        ]);
    }

    public function update(UpdateSeoSettingsRequest $request): RedirectResponse
    {
        $this->seoSettingsService->saveGoogleSeoScript(
            $request->settingsPayload()['google_seo_script'],
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.settings.seo.edit')
            ->with('status', __('SEO settings updated successfully.'));
    }
}
