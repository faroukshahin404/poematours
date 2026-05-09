<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\UpdatePaymentSettingsRequest;
use App\Services\Dashboard\Admin\PaymentSettingsService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PaymentSettingsController extends Controller
{
    public function __construct(
        private readonly PaymentSettingsService $paymentSettingsService
    ) {}

    public function edit(): Response
    {
        return Inertia::render('Dashboard/Admin/Settings/Payment', [
            'settings' => $this->paymentSettingsService->stripeSettingsForForm(),
        ]);
    }

    public function update(UpdatePaymentSettingsRequest $request): RedirectResponse
    {
        $this->paymentSettingsService->saveStripeSettings(
            $request->settingsPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.settings.payment.edit')
            ->with('status', __('Payment settings updated successfully.'));
    }
}
