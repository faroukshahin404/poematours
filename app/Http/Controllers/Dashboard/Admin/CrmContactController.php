<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\CrmContactRepositoryInterface;
use App\Exports\CrmContactsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreCrmContactRequest;
use App\Http\Requests\Dashboard\Admin\UpdateCrmContactArchiveRequest;
use App\Http\Requests\Dashboard\Admin\UpdateCrmContactRequest;
use App\Http\Requests\Dashboard\Admin\UpdateCrmContactStatusRequest;
use App\Models\CrmContact;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CrmContactController extends Controller
{
    public function __construct(
        private readonly CrmContactRepositoryInterface $contacts
    ) {}

    public function index(): Response
    {
        $statusOptions = collect(CrmContact::statusLabels())
            ->map(fn ($label, $value) => ['value' => $value, 'label' => $label])
            ->values();

        return Inertia::render('Dashboard/Admin/CrmContacts/Index', [
            'contacts' => $this->contacts->paginate(request()->only([
                'search',
                'status',
                'country_id',
                'service_id',
                'created_by',
                'updated_by',
                'archived',
                'source',
                'created_from',
                'created_to',
            ]), 15)->withQueryString(),
            'filters' => request()->only([
                'search',
                'status',
                'country_id',
                'service_id',
                'created_by',
                'updated_by',
                'archived',
                'source',
                'created_from',
                'created_to',
                'view',
            ]),
            'statusOptions' => $statusOptions,
            'sourceOptions' => collect(CrmContact::sourceLabels())
                ->map(fn ($label, $value) => ['value' => $value, 'label' => $label])
                ->values(),
            'countries' => $this->contacts->countryOptions(),
            'services' => $this->contacts->serviceOptions(),
            'users' => $this->contacts->userOptions(),
        ]);
    }

    public function create(): Response
    {
        $statusOptions = collect(CrmContact::statusLabels())
            ->map(fn ($label, $value) => ['value' => $value, 'label' => $label])
            ->values();

        return Inertia::render('Dashboard/Admin/CrmContacts/Create', [
            'countries' => $this->contacts->countryOptions(),
            'services' => $this->contacts->serviceOptions(),
            'statusOptions' => $statusOptions,
            'sourceOptions' => collect(CrmContact::sourceLabels())
                ->map(fn ($label, $value) => ['value' => $value, 'label' => $label])
                ->values(),
        ]);
    }

    public function store(StoreCrmContactRequest $request): RedirectResponse
    {
        $this->contacts->create(
            $request->contactPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.crm-contacts.index')
            ->with('status', __('Contact created successfully.'));
    }

    public function edit(CrmContact $crmContact): Response
    {
        return Inertia::render('Dashboard/Admin/CrmContacts/Edit', [
            'contact' => [
                'id' => $crmContact->id,
                'name' => $crmContact->name,
                'phone' => $crmContact->phone,
                'email' => $crmContact->email,
                'country_id' => $crmContact->country_id,
                'status' => $crmContact->status,
                'source' => $crmContact->source,
                'service_ids' => $crmContact->services()->pluck('crm_services.id')->all(),
                'notes' => $crmContact->notes,
            ],
            'countries' => $this->contacts->countryOptions(),
            'services' => $this->contacts->serviceOptions(),
            'statusOptions' => collect(CrmContact::statusLabels())
                ->map(fn ($label, $value) => ['value' => $value, 'label' => $label])
                ->values(),
            'sourceOptions' => collect(CrmContact::sourceLabels())
                ->map(fn ($label, $value) => ['value' => $value, 'label' => $label])
                ->values(),
        ]);
    }

    public function update(UpdateCrmContactRequest $request, CrmContact $crmContact): RedirectResponse
    {
        $this->contacts->update(
            $crmContact,
            $request->contactPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.crm-contacts.index')
            ->with('status', __('Contact updated successfully.'));
    }

    public function destroy(CrmContact $crmContact): RedirectResponse
    {
        $this->contacts->delete($crmContact);

        return redirect()
            ->route('admin.crm-contacts.index')
            ->with('status', __('Contact deleted successfully.'));
    }

    public function updateStatus(UpdateCrmContactStatusRequest $request, CrmContact $crmContact): RedirectResponse
    {
        $this->contacts->updateStatus(
            $crmContact,
            $request->validated()['status'],
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.crm-contacts.index', request()->query())
            ->with('status', __('Contact status updated successfully.'));
    }

    public function updateArchiveState(UpdateCrmContactArchiveRequest $request, CrmContact $crmContact): RedirectResponse
    {
        $archived = (bool) $request->boolean('archived');

        $this->contacts->updateArchiveState(
            $crmContact,
            $archived,
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.crm-contacts.index', request()->query())
            ->with('status', $archived ? __('Contact archived successfully.') : __('Contact restored successfully.'));
    }

    public function export(): BinaryFileResponse
    {
        $filters = request()->only([
            'search',
            'status',
            'country_id',
            'service_id',
            'created_by',
            'updated_by',
            'archived',
            'source',
            'created_from',
            'created_to',
        ]);

        $fileName = 'crm-contacts-'.now()->format('Ymd-His').'.xlsx';

        return Excel::download(
            new CrmContactsExport($this->contacts->exportRows($filters)),
            $fileName,
        );
    }
}
