<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\ReelRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreReelRequest;
use App\Http\Requests\Dashboard\Admin\UpdateReelRequest;
use App\Models\Language;
use App\Models\Reel;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReelController extends Controller
{
    public function __construct(
        private readonly ReelRepositoryInterface $reels
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Reels/Index', [
            'reels' => $this->reels->paginate(15)->through(function (Reel $reel) {
                return [
                    'id' => $reel->id,
                    'name' => $reel->labelForDefaultLanguage(),
                    'description' => (string) ($reel->descriptionTranslations()[Language::defaultSlug()] ?? ''),
                    'video_url' => $reel->videoPublicUrl(),
                    'creator' => $reel->creator?->name,
                    'updater' => $reel->updater?->name,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Reels/Create');
    }

    public function store(StoreReelRequest $request): RedirectResponse
    {
        $this->reels->create($request->reelPayload(), (int) $request->user()->id);

        return redirect()->route('admin.reels.index')->with('status', __('Reel created successfully.'));
    }

    public function edit(Reel $reel): Response
    {
        return Inertia::render('Dashboard/Admin/Reels/Edit', [
            'reel' => [
                'id' => $reel->id,
                'name_translations' => $reel->nameTranslations(),
                'description_translations' => $reel->descriptionTranslations(),
                'video_url' => $reel->getRawOriginal('video_url'),
                'video_public_url' => $reel->videoPublicUrl(),
            ],
        ]);
    }

    public function update(UpdateReelRequest $request, Reel $reel): RedirectResponse
    {
        $this->reels->update($reel, $request->reelPayload(), (int) $request->user()->id);

        return redirect()->route('admin.reels.index')->with('status', __('Reel updated successfully.'));
    }

    public function destroy(Reel $reel): RedirectResponse
    {
        $this->reels->delete($reel);

        return redirect()->route('admin.reels.index')->with('status', __('Reel deleted successfully.'));
    }
}
