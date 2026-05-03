<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\ActivityRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreActivityRequest;
use App\Http\Requests\Dashboard\Admin\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\Destination;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActivityController extends Controller
{
    public function __construct(
        private readonly ActivityRepositoryInterface $activities
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Activities/Index', [
            'activities' => $this->activities->paginate(15)->through(function (Activity $activity) {
                return [
                    'id' => $activity->id,
                    'slug' => $activity->slug,
                    'name' => $activity->labelForDefaultLanguage(),
                    'destination' => $activity->destination?->labelForDefaultLanguage(),
                    'description' => (string) ($activity->descriptionTranslations()[Language::defaultSlug()] ?? ''),
                    'image_url' => $activity->imagePublicUrl(),
                    'packages_count' => $activity->packages_count,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Activities/Create', [
            'destinations' => $this->destinationOptions(),
        ]);
    }

    public function store(StoreActivityRequest $request): RedirectResponse
    {
        $this->activities->create(
            $request->activityPayload(),
            $request->file('image'),
            (int) $request->user()->id,
        );

        return redirect()->route('admin.activities.index')->with('status', __('Activity created successfully.'));
    }

    public function edit(Activity $activity): Response
    {
        return Inertia::render('Dashboard/Admin/Activities/Edit', [
            'activity' => [
                'id' => $activity->id,
                'destination_id' => $activity->destination_id,
                'name_translations' => $activity->nameTranslations(),
                'description_translations' => $activity->descriptionTranslations(),
                'image_url' => $activity->imagePublicUrl(),
            ],
            'destinations' => $this->destinationOptions(),
        ]);
    }

    public function update(UpdateActivityRequest $request, Activity $activity): RedirectResponse
    {
        $this->activities->update(
            $activity,
            $request->activityPayload(),
            $request->file('image'),
            (int) $request->user()->id,
        );

        return redirect()->route('admin.activities.index')->with('status', __('Activity updated successfully.'));
    }

    public function destroy(Request $request, Activity $activity): RedirectResponse
    {
        $this->activities->delete($activity, (int) $request->user()->id);

        return redirect()->route('admin.activities.index')->with('status', __('Activity deleted successfully.'));
    }

    /**
     * @return array<int, array{id: int, slug: string, label: string}>
     */
    private function destinationOptions(): array
    {
        return Destination::query()
            ->orderBy('slug')
            ->get(['id', 'slug', 'name'])
            ->map(fn (Destination $destination) => [
                'id' => $destination->id,
                'slug' => $destination->slug,
                'label' => $destination->labelForDefaultLanguage(),
            ])
            ->values()
            ->all();
    }
}
