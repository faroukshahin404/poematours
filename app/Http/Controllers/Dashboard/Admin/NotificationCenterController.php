<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use App\Services\Dashboard\Admin\NotificationCenterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationCenterController extends Controller
{
    public function __construct(
        private readonly NotificationCenterService $notificationCenterService
    ) {}

    public function markAsRead(Request $request, SystemNotification $notification): RedirectResponse
    {
        $this->notificationCenterService->markAsRead($notification);

        return back();
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $this->notificationCenterService->markAllAsRead();

        return back();
    }
}
