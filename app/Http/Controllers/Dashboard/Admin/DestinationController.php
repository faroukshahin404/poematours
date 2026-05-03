<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\DestinationRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreDestinationRequest;
use App\Http\Requests\Dashboard\Admin\UpdateDestinationRequest;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DestinationController extends Controller
{
    public function __construct(
        private readonly DestinationRepositoryInterface $destinations
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Destinations/Index', [
            'destinations' => $this->destinations->paginate(15)->through(function (Destination $destination) {
                return [
                    'id' => $destination->id,
                    'slug' => $destination->slug,
                    'label' => $destination->labelForDefaultLanguage(),
                    'lat' => $destination->lat !== null ? (float) $destination->lat : null,
                    'lng' => $destination->lng !== null ? (float) $destination->lng : null,
                    'image_url' => $destination->imagePublicUrl(),
                    'creator' => $destination->creator?->name,
                    'updater' => $destination->updater?->name,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Destinations/Create');
    }

    public function store(StoreDestinationRequest $request): RedirectResponse
    {
        $this->destinations->create(
            $request->destinationPayload(),
            $request->file('image'),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.destinations.index')
            ->with('status', __('Destination created successfully.'));
    }

    public function edit(Destination $destination): Response
    {
        return Inertia::render('Dashboard/Admin/Destinations/Edit', [
            'destination' => [
                'id' => $destination->id,
                'slug' => $destination->slug,
                'name_translations' => $destination->nameTranslations(),
                'lat' => $destination->lat !== null ? (float) $destination->lat : null,
                'lng' => $destination->lng !== null ? (float) $destination->lng : null,
                'image_url' => $destination->imagePublicUrl(),
            ],
        ]);
    }

    public function update(UpdateDestinationRequest $request, Destination $destination): RedirectResponse
    {
        $this->destinations->update(
            $destination,
            $request->destinationPayload(),
            $request->file('image'),
            (int) $request->user()->id,
        );

        return redirect()
            ->route('admin.destinations.index')
            ->with('status', __('Destination updated successfully.'));
    }

    public function destroy(Request $request, Destination $destination): RedirectResponse
    {
        $this->destinations->delete($destination, (int) $request->user()->id);

        return redirect()
            ->route('admin.destinations.index')
            ->with('status', __('Destination deleted successfully.'));
    }
}
