<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\PackageInclusionRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StorePackageInclusionRequest;
use App\Http\Requests\Dashboard\Admin\UpdatePackageInclusionRequest;
use App\Models\PackageInclusion;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PackageInclusionController extends Controller
{
    public function __construct(
        private readonly PackageInclusionRepositoryInterface $packageInclusions
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/PackageInclusions/Index', [
            'packageInclusions' => $this->packageInclusions->paginate(15)->through(function (PackageInclusion $row) {
                return [
                    'id' => $row->id,
                    'name' => $row->labelForDefaultLanguage(),
                    'icon' => $row->icon,
                    'packages_count' => $row->packages_count,
                    'created_by' => $row->creator?->name,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/PackageInclusions/Create');
    }

    public function store(StorePackageInclusionRequest $request): RedirectResponse
    {
        $this->packageInclusions->create(
            $request->inclusionPayload(),
            (int) $request->user()->id,
        );

        return redirect()->route('admin.package-inclusions.index')->with('status', __('Package inclusion created successfully.'));
    }

    public function edit(PackageInclusion $packageInclusion): Response
    {
        return Inertia::render('Dashboard/Admin/PackageInclusions/Edit', [
            'packageInclusion' => [
                'id' => $packageInclusion->id,
                'name_translations' => $packageInclusion->nameTranslations(),
                'icon' => $packageInclusion->icon,
                'created_by_name' => $packageInclusion->creator?->name,
            ],
        ]);
    }

    public function update(UpdatePackageInclusionRequest $request, PackageInclusion $packageInclusion): RedirectResponse
    {
        $this->packageInclusions->update(
            $packageInclusion,
            $request->inclusionPayload(),
            (int) $request->user()->id,
        );

        return redirect()->route('admin.package-inclusions.index')->with('status', __('Package inclusion updated successfully.'));
    }

    public function destroy(PackageInclusion $packageInclusion): RedirectResponse
    {
        $this->packageInclusions->delete($packageInclusion);

        return redirect()->route('admin.package-inclusions.index')->with('status', __('Package inclusion deleted successfully.'));
    }
}
