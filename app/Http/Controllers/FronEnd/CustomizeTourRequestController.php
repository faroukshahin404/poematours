<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FronEnd\StoreCustomizeTourRequest;
use App\Models\TravelPackage;
use App\Services\Frontend\CustomizeTourRequestService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomizeTourRequestController extends Controller
{
    public function __construct(
        private readonly CustomizeTourRequestService $customizeTourRequestService
    ) {
    }

    public function create(Request $request): View
    {
        $selectedPackage = null;
        $packageId = $request->integer('package_id') ?: (int) old('package_id');

        if ($packageId > 0) {
            $selectedPackage = TravelPackage::query()->find($packageId);
        }

        return view('frontend.customize.index', [
            'selectedPackage' => $selectedPackage,
        ]);
    }

    public function store(StoreCustomizeTourRequest $request): RedirectResponse
    {
        $this->customizeTourRequestService->create($request->requestPayload());

        return redirect()
            ->route('customize.create')
            ->with('status', 'Your request was submitted. Our Egypt travel team will contact you soon.');
    }
}
