<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FronEnd\StoreCustomizeTourRequest;
use App\Services\Frontend\CustomizeTourRequestService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CustomizeTourRequestController extends Controller
{
    public function __construct(
        private readonly CustomizeTourRequestService $customizeTourRequestService
    ) {
    }

    public function create(): View
    {
        return view('frontend.customize.index');
    }

    public function store(StoreCustomizeTourRequest $request): RedirectResponse
    {
        $this->customizeTourRequestService->create($request->requestPayload());

        return redirect()
            ->route('customize.create')
            ->with('status', 'Your request was submitted. Our Egypt travel team will contact you soon.');
    }
}
