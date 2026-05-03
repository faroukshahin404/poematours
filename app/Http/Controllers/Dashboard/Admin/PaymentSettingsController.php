<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\UpdatePaymentSettingsRequest;
use App\Http\Requests\Dashboard\Admin\UpdateReservationAddonSettingsRequest;
use App\Services\Settings\SettingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PaymentSettingsController extends Controller
{
    public function __construct(
        private readonly SettingService $settingService
    ) {
    }

    public function edit(): Response
    {
        $paymentSettings = $this->settingService->paymentSettings();

        return Inertia::render('Dashboard/Admin/Settings/Payments', [
            'settings' => [
                'stripe_publishable_key' => $paymentSettings['publishable_key'],
                'stripe_secret_key' => $paymentSettings['secret_key'],
                'stripe_webhook_secret' => $paymentSettings['webhook_secret'],
                'stripe_mode' => $paymentSettings['mode'],
                'stripe_enabled' => $paymentSettings['enabled'],
                'reservation_currency' => $paymentSettings['currency'],
                'reservation_deposit_percentage' => $paymentSettings['deposit_percentage'],
            ],
        ]);
    }

    public function update(UpdatePaymentSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->settingService->putMany([
            SettingService::KEY_STRIPE_PUBLISHABLE => $validated['stripe_publishable_key'] ?? '',
            SettingService::KEY_STRIPE_SECRET => $validated['stripe_secret_key'] ?? '',
            SettingService::KEY_STRIPE_WEBHOOK => $validated['stripe_webhook_secret'] ?? '',
            SettingService::KEY_STRIPE_MODE => $validated['stripe_mode'],
            SettingService::KEY_STRIPE_ENABLED => (bool) ($validated['stripe_enabled'] ?? false),
            SettingService::KEY_RESERVATION_CURRENCY => strtoupper((string) $validated['reservation_currency']),
            SettingService::KEY_RESERVATION_DEPOSIT_PERCENTAGE => (int) $validated['reservation_deposit_percentage'],
        ]);

        return back()->with('success', 'Payment settings were updated.');
    }

    public function editReservationAddons(): Response
    {
        return Inertia::render('Dashboard/Admin/Settings/ReservationAddons', [
            'settings' => [
                'reservation_addon_groups' => $this->settingService->reservationAddonGroups(),
            ],
        ]);
    }

    public function updateReservationAddons(UpdateReservationAddonSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $addonGroups = array_values($validated['reservation_addon_groups'] ?? []);

        $this->settingService->put(SettingService::KEY_RESERVATION_ADDON_GROUPS, $addonGroups);

        return back()->with('success', 'Reservation add-ons settings were updated.');
    }
}
