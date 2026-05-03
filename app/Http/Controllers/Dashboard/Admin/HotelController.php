<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\HotelRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreHotelRequest;
use App\Http\Requests\Dashboard\Admin\UpdateHotelRequest;
use App\Models\Destination;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HotelController extends Controller
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotels
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Hotels/Index', [
            'hotels' => $this->hotels->paginate(15)->through(function (Hotel $hotel) {
                return [
                    'id' => $hotel->id,
                    'slug' => $hotel->slug,
                    'name' => $hotel->labelForDefaultLanguage(),
                    'description' => $hotel->descriptionForDefaultLanguage(),
                    'destination' => $hotel->destination?->labelForDefaultLanguage(),
                    'stars' => $hotel->stars,
                    'rooms_count' => $hotel->rooms_count,
                    'images' => $hotel->media->map(fn ($m) => ['id' => $m->id, 'url' => $m->publicUrl()])->values(),
                    'creator' => $hotel->creator?->name,
                    'updater' => $hotel->updater?->name,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Hotels/Create', [
            'destinations' => $this->destinationOptions(),
        ]);
    }

    public function store(StoreHotelRequest $request): RedirectResponse
    {
        $this->hotels->create(
            $request->hotelPayload(),
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.hotels.index')
            ->with('status', __('Hotel created successfully.'));
    }

    public function edit(Hotel $hotel): Response
    {
        $hotel->load(['media:id,path,model_type,model_id']);

        return Inertia::render('Dashboard/Admin/Hotels/Edit', [
            'hotel' => [
                'id' => $hotel->id,
                'slug' => $hotel->slug,
                'destination_id' => $hotel->destination_id,
                'stars' => $hotel->stars,
                'name_translations' => $hotel->nameTranslations(),
                'description_translations' => $hotel->descriptionTranslations(),
                'images' => $hotel->media->map(fn ($m) => ['id' => $m->id, 'url' => $m->publicUrl()])->values(),
            ],
            'destinations' => $this->destinationOptions(),
        ]);
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel): RedirectResponse
    {
        $this->hotels->update(
            $hotel,
            $request->hotelPayload(),
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.hotels.index')
            ->with('status', __('Hotel updated successfully.'));
    }

    public function destroy(Request $request, Hotel $hotel): RedirectResponse
    {
        $this->hotels->delete($hotel, (int) $request->user()->id);

        return redirect()
            ->route('admin.hotels.index')
            ->with('status', __('Hotel deleted successfully.'));
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
