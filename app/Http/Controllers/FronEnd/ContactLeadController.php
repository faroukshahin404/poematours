<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FronEnd\StoreNewsletterContactRequest;
use App\Http\Requests\FronEnd\StorePackageExpertContactRequest;
use App\Http\Requests\FronEnd\StoreWebsiteContactRequest;
use App\Services\Frontend\ContactLeadService;
use Illuminate\Http\RedirectResponse;

class ContactLeadController extends Controller
{
    public function __construct(
        private readonly ContactLeadService $contactLeadService
    ) {}

    public function storeWebsite(StoreWebsiteContactRequest $request): RedirectResponse
    {
        $this->contactLeadService->createWebsiteLead($request->payload());

        return back()->with('status', 'Thanks! Our team will contact you soon.');
    }

    public function storeNewsletter(StoreNewsletterContactRequest $request): RedirectResponse
    {
        $this->contactLeadService->createNewsletterLead($request->payload());

        return back()->with('status', 'Thanks for subscribing to our newsletter.');
    }

    public function storePackageExpert(StorePackageExpertContactRequest $request): RedirectResponse
    {
        $this->contactLeadService->createWebsiteLead($request->contactPayload());

        return back()->with('expert_enquiry_success', true);
    }
}
