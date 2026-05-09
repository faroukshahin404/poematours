<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\UpdateGeneralSettingsRequest;
use App\Services\Dashboard\Admin\GeneralSettingsService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class GeneralSettingsController extends Controller
{
    public function __construct(
        private readonly GeneralSettingsService $generalSettingsService
    ) {}

    public function edit(): Response
    {
        return Inertia::render('Dashboard/Admin/Settings/General', [
            'settings' => [
                'notification_emails' => $this->generalSettingsService->notificationEmails(),
            ],
        ]);
    }

    public function update(UpdateGeneralSettingsRequest $request): RedirectResponse
    {
        $this->generalSettingsService->saveNotificationEmails(
            $request->settingsPayload()['notification_emails'],
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.settings.general.edit')
            ->with('status', __('General settings updated successfully.'));
    }
}
