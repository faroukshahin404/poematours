<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\CrmServiceRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreCrmServiceRequest;
use App\Http\Requests\Dashboard\Admin\UpdateCrmServiceRequest;
use App\Models\CrmService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CrmServiceController extends Controller
{
    public function __construct(
        private readonly CrmServiceRepositoryInterface $services
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/CrmServices/Index', [
            'services' => $this->services->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/CrmServices/Create');
    }

    public function store(StoreCrmServiceRequest $request): RedirectResponse
    {
        $this->services->create($request->servicePayload(), (int) $request->user()->id);

        return redirect()->route('admin.crm-services.index')->with('status', __('Service created successfully.'));
    }

    public function edit(CrmService $crmService): Response
    {
        return Inertia::render('Dashboard/Admin/CrmServices/Edit', [
            'service' => [
                'id' => $crmService->id,
                'name' => $crmService->name,
            ],
        ]);
    }

    public function update(UpdateCrmServiceRequest $request, CrmService $crmService): RedirectResponse
    {
        $this->services->update($crmService, $request->servicePayload(), (int) $request->user()->id);

        return redirect()->route('admin.crm-services.index')->with('status', __('Service updated successfully.'));
    }

    public function destroy(CrmService $crmService): RedirectResponse
    {
        $this->services->delete($crmService);

        return redirect()->route('admin.crm-services.index')->with('status', __('Service deleted successfully.'));
    }
}
