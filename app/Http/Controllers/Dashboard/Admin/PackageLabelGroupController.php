<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\PackageLabelGroupRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StorePackageLabelGroupRequest;
use App\Http\Requests\Dashboard\Admin\UpdatePackageLabelGroupRequest;
use App\Models\PackageLabelGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PackageLabelGroupController extends Controller
{
    public function __construct(
        private readonly PackageLabelGroupRepositoryInterface $packageLabelGroups
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/PackageLabelGroups/Index', [
            'packageLabelGroups' => $this->packageLabelGroups->paginate(15)->through(function (PackageLabelGroup $group) {
                return [
                    'id' => $group->id,
                    'slug' => $group->slug,
                    'label' => $group->labelForDefaultLanguage(),
                    'labels_count' => $group->labels_count,
                    'creator' => $group->creator?->name,
                    'updater' => $group->updater?->name,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/PackageLabelGroups/Create');
    }

    public function store(StorePackageLabelGroupRequest $request): RedirectResponse
    {
        $this->packageLabelGroups->create(
            $request->groupPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.package-label-groups.index')
            ->with('status', __('Package label group created successfully.'));
    }

    public function edit(PackageLabelGroup $packageLabelGroup): Response
    {
        return Inertia::render('Dashboard/Admin/PackageLabelGroups/Edit', [
            'packageLabelGroup' => [
                'id' => $packageLabelGroup->id,
                'slug' => $packageLabelGroup->slug,
                'name_translations' => $packageLabelGroup->nameTranslations(),
            ],
        ]);
    }

    public function update(UpdatePackageLabelGroupRequest $request, PackageLabelGroup $packageLabelGroup): RedirectResponse
    {
        $this->packageLabelGroups->update(
            $packageLabelGroup,
            $request->groupPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.package-label-groups.index')
            ->with('status', __('Package label group updated successfully.'));
    }

    public function destroy(Request $request, PackageLabelGroup $packageLabelGroup): RedirectResponse
    {
        if ($packageLabelGroup->labels()->exists()) {
            return redirect()
                ->route('admin.package-label-groups.index')
                ->withErrors([
                    'delete' => __('Cannot delete this group while it still has labels. Remove or move those labels first.'),
                ]);
        }

        $this->packageLabelGroups->delete($packageLabelGroup, (int) $request->user()->id);

        return redirect()
            ->route('admin.package-label-groups.index')
            ->with('status', __('Package label group deleted successfully.'));
    }
}
