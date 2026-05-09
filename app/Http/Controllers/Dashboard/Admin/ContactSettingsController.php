<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\UpdateContactSettingsRequest;
use App\Services\Dashboard\Admin\ContactSettingsService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactSettingsController extends Controller
{
    public function __construct(
        private readonly ContactSettingsService $contactSettingsService
    ) {}

    public function edit(): Response
    {
        return Inertia::render('Dashboard/Admin/Settings/Contact', [
            'settings' => $this->contactSettingsService->contactSettingsForForm(),
        ]);
    }

    public function update(UpdateContactSettingsRequest $request): RedirectResponse
    {
        $this->contactSettingsService->saveContactSettings(
            $request->settingsPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.settings.contact.edit')
            ->with('status', __('Contact settings updated successfully.'));
    }
}
