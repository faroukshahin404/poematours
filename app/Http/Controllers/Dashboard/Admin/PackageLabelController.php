<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\PackageLabelRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StorePackageLabelRequest;
use App\Http\Requests\Dashboard\Admin\UpdatePackageLabelRequest;
use App\Models\PackageLabel;
use App\Models\PackageLabelGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PackageLabelController extends Controller
{
    public function __construct(
        private readonly PackageLabelRepositoryInterface $packageLabels
    ) {}

    public function index(Request $request): Response
    {
        $filterGroupId = null;
        if ($request->filled('package_label_group_id')) {
            $id = (int) $request->input('package_label_group_id');
            $filterGroupId = $id > 0 ? $id : null;
        }

        $groupOptions = PackageLabelGroup::query()
            ->orderBy('slug')
            ->get(['id', 'slug', 'name'])
            ->map(fn (PackageLabelGroup $g) => [
                'id' => $g->id,
                'slug' => $g->slug,
                'label' => $g->labelForDefaultLanguage(),
            ])
            ->values()
            ->all();

        return Inertia::render('Dashboard/Admin/PackageLabels/Index', [
            'packageLabels' => $this->packageLabels->paginate($filterGroupId, 15)->through(function (PackageLabel $label) {
                return [
                    'id' => $label->id,
                    'slug' => $label->slug,
                    'label' => $label->labelForDefaultLanguage(),
                    'group_id' => $label->package_label_group_id,
                    'group_slug' => $label->group?->slug,
                    'group_label' => $label->group?->labelForDefaultLanguage(),
                    'creator' => $label->creator?->name,
                    'updater' => $label->updater?->name,
                ];
            }),
            'groupOptions' => $groupOptions,
            'filterGroupId' => $filterGroupId,
        ]);
    }

    public function create(): Response
    {
        $groupOptions = PackageLabelGroup::query()
            ->orderBy('slug')
            ->get(['id', 'slug', 'name'])
            ->map(fn (PackageLabelGroup $g) => [
                'id' => $g->id,
                'slug' => $g->slug,
                'label' => $g->labelForDefaultLanguage(),
            ])
            ->values()
            ->all();

        return Inertia::render('Dashboard/Admin/PackageLabels/Create', [
            'groupOptions' => $groupOptions,
        ]);
    }

    public function store(StorePackageLabelRequest $request): RedirectResponse
    {
        $this->packageLabels->create(
            $request->labelPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.package-labels.index')
            ->with('status', __('Package label created successfully.'));
    }

    public function edit(PackageLabel $packageLabel): Response
    {
        $groupOptions = PackageLabelGroup::query()
            ->orderBy('slug')
            ->get(['id', 'slug', 'name'])
            ->map(fn (PackageLabelGroup $g) => [
                'id' => $g->id,
                'slug' => $g->slug,
                'label' => $g->labelForDefaultLanguage(),
            ])
            ->values()
            ->all();

        return Inertia::render('Dashboard/Admin/PackageLabels/Edit', [
            'packageLabel' => [
                'id' => $packageLabel->id,
                'slug' => $packageLabel->slug,
                'package_label_group_id' => $packageLabel->package_label_group_id,
                'name_translations' => $packageLabel->nameTranslations(),
            ],
            'groupOptions' => $groupOptions,
        ]);
    }

    public function update(UpdatePackageLabelRequest $request, PackageLabel $packageLabel): RedirectResponse
    {
        $this->packageLabels->update(
            $packageLabel,
            $request->labelPayload(),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.package-labels.index')
            ->with('status', __('Package label updated successfully.'));
    }

    public function destroy(Request $request, PackageLabel $packageLabel): RedirectResponse
    {
        $this->packageLabels->delete($packageLabel, (int) $request->user()->id);

        return redirect()
            ->route('admin.package-labels.index')
            ->with('status', __('Package label deleted successfully.'));
    }
}
