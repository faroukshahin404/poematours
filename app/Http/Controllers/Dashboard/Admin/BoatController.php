<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\BoatRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreBoatRequest;
use App\Http\Requests\Dashboard\Admin\UpdateBoatRequest;
use App\Models\Boat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BoatController extends Controller
{
    public function __construct(
        private readonly BoatRepositoryInterface $boats
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Boats/Index', [
            'boats' => $this->boats->paginate(15)->through(function (Boat $boat) {
                return [
                    'id' => $boat->id,
                    'slug' => $boat->slug,
                    'name' => $boat->labelForDefaultLanguage(),
                    'boat_image' => $boat->sectionImageUrl('boat_image'),
                    'suite_image' => $boat->sectionImageUrl('suite_image'),
                    'food_image' => $boat->sectionImageUrl('food_image'),
                    'wellness_image' => $boat->sectionImageUrl('wellness_image'),
                    'gallery_count' => $boat->media->count(),
                    'creator' => $boat->creator?->name,
                    'updater' => $boat->updater?->name,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Boats/Create');
    }

    public function store(StoreBoatRequest $request): RedirectResponse
    {
        $this->boats->create(
            $request->boatPayload(),
            $request->file('boat_image'),
            $request->file('suite_image'),
            $request->file('food_image'),
            $request->file('wellness_image'),
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.boats.index')
            ->with('status', __('Boat created successfully.'));
    }

    public function edit(Boat $boat): Response
    {
        $boat->load(['media:id,path,model_type,model_id']);

        return Inertia::render('Dashboard/Admin/Boats/Edit', [
            'boat' => [
                'id' => $boat->id,
                'slug' => $boat->slug,
                'name_translations' => $boat->nameTranslations(),
                'boat_translations' => $boat->boatTranslations(),
                'suite_translations' => $boat->suiteTranslations(),
                'food_translations' => $boat->foodTranslations(),
                'wellness_translations' => $boat->wellnessTranslations(),
                'boat_image' => $boat->sectionImageUrl('boat_image'),
                'suite_image' => $boat->sectionImageUrl('suite_image'),
                'food_image' => $boat->sectionImageUrl('food_image'),
                'wellness_image' => $boat->sectionImageUrl('wellness_image'),
                'images' => $boat->media->map(fn ($m) => ['id' => $m->id, 'url' => $m->publicUrl()])->values(),
            ],
        ]);
    }

    public function update(UpdateBoatRequest $request, Boat $boat): RedirectResponse
    {
        $this->boats->update(
            $boat,
            $request->boatPayload(),
            $request->file('boat_image'),
            $request->file('suite_image'),
            $request->file('food_image'),
            $request->file('wellness_image'),
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.boats.index')
            ->with('status', __('Boat updated successfully.'));
    }

    public function destroy(Request $request, Boat $boat): RedirectResponse
    {
        $this->boats->delete($boat, (int) $request->user()->id);

        return redirect()
            ->route('admin.boats.index')
            ->with('status', __('Boat deleted successfully.'));
    }
}
