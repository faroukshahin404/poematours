<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FronEnd\StoreReservationRequest;
use App\Services\Frontend\ReservationService;
use App\Services\Settings\SettingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationService $reservationService,
        private readonly SettingService $settingService,
    ) {
    }

    public function create(): View
    {
        $paymentSettings = $this->settingService->paymentSettings();

        return view('frontend.reservation.index', [
            'addonGroups' => $this->settingService->activeReservationAddonGroups(),
            'currency' => $paymentSettings['currency'],
            'depositPercentage' => $paymentSettings['deposit_percentage'],
        ]);
    }

    public function createPaymentIntent(StoreReservationRequest $request): JsonResponse
    {
        return response()->json($this->reservationService->createPaymentIntent($request->reservationPayload()));
    }

    public function success(Request $request): View
    {
        return view('frontend.reservation.success', [
            'reservationUuid' => $request->string('reservation')->toString(),
        ]);
    }

    public function cancel(Request $request): View
    {
        return view('frontend.reservation.cancel', [
            'reservationUuid' => $request->string('reservation')->toString(),
        ]);
    }
}
