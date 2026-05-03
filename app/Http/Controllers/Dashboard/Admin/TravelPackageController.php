<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\TravelPackageRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StoreTravelPackageRequest;
use App\Http\Requests\Dashboard\Admin\UpdateTravelPackageRequest;
use App\Models\Activity;
use App\Models\Boat;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\PackageCategory;
use App\Models\PackageLabel;
use App\Models\PackageLabelGroup;
use App\Models\TravelPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TravelPackageController extends Controller
{
    public function __construct(
        private readonly TravelPackageRepositoryInterface $packages
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Packages/Index', [
            'packages' => $this->packages->paginate(15)->through(function (TravelPackage $package) {
                return [
                    'id' => $package->id,
                    'slug' => $package->slug,
                    'title' => $package->title,
                    'categories' => $package->categories->map(fn ($c) => $c->labelForDefaultLanguage())->values(),
                    'itineraries_count' => $package->itineraries_count,
                    'is_featured' => $package->featured==1?true:false,
                    'is_recommended' => $package->recommended==1?true:false,
                ];
            }),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Admin/Packages/Create', $this->formOptions());
    }

    public function store(StoreTravelPackageRequest $request): RedirectResponse
    {
        $this->packages->create(
            $request->packagePayload(),
            $request->file('pdf'),
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()->route('admin.packages.index')->with('status', __('Package created successfully.'));
    }

    public function edit(TravelPackage $package): Response
    {
        $package->load([
            'categories:id',
            'labelGroups:id',
            'labels:id',
            'activities:id',
            'media:id,path,model_type,model_id',
            'itineraries:id,package_id,title,description,breakfast,lunch,dinner,snacks,destination_id,hotel_id,boat_id,sort_order',
            'datePrices.accommodations:id,package_date_price_id,hotel_id,room_id',
        ]);
        return Inertia::render('Dashboard/Admin/Packages/Edit', array_merge($this->formOptions(), [
            'package' => [
                'id' => $package->id,
                'slug' => $package->slug,
                'recommended' => $package->recommended,
                'featured' => $package->featured,
                'title_translations' => $package->titleTranslations(),
                'description_translations' => $package->descriptionTranslations(),
                'details' => $package->detailsData(),
                'pdf_url' => $package->pdf ? asset('storage/'.$package->pdf) : null,
                'selected_category_ids' => $package->categories->pluck('id')->values(),
                'selected_label_group_ids' => $package->labelGroups->pluck('id')->values(),
                'selected_label_ids' => $package->labels->pluck('id')->values(),
                'selected_activity_ids' => $package->activities->pluck('id')->values(),
                'gallery' => $package->media->map(fn ($m) => ['id' => $m->id, 'url' => $m->publicUrl()])->values(),
                'itineraries' => $package->itineraries->sortBy('sort_order')->values()->map(fn ($i) => [
                    'title' => $i->title,
                    'description' => $i->description,
                    'meals_included' => [
                        'breakfast' => (bool) $i->breakfast,
                        'lunch' => (bool) $i->lunch,
                        'dinner' => (bool) $i->dinner,
                        'snacks' => (bool) $i->snacks,
                    ],
                    'destination_id' => $i->destination_id,
                    'hotel_id' => $i->hotel_id,
                    'boat_id' => $i->boat_id,
                ]),
                'date_prices' => $package->datePrices->values()->map(fn ($dp) => [
                    'from_date' => (string) $dp->from_date,
                    'to_date' => (string) $dp->to_date,
                    'available_seats' => $dp->available_seats,
                    'price' => (float) $dp->price,
                    'offer' => $dp->offer !== null ? (float) $dp->offer : null,
                    'accommodations' => $dp->accommodations->map(fn ($a) => [
                        'hotel_id' => $a->hotel_id,
                        'room_id' => $a->room_id,
                    ])->values(),
                ]),
            ],
        ]));
    }

    public function update(UpdateTravelPackageRequest $request, TravelPackage $package): RedirectResponse
    {
        $this->packages->update(
            $package,
            $request->packagePayload(),
            $request->file('pdf'),
            $request->file('images', []),
            (int) $request->user()->id,
        );

        return redirect()->route('admin.packages.index')->with('status', __('Package updated successfully.'));
    }

    public function destroy(Request $request, TravelPackage $package): RedirectResponse
    {
        $this->packages->delete($package, (int) $request->user()->id);

        return redirect()->route('admin.packages.index')->with('status', __('Package deleted successfully.'));
    }

    public function duplicate(Request $request, TravelPackage $package): RedirectResponse
    {
        $this->packages->duplicate($package, (int) $request->user()->id);

        return redirect()->route('admin.packages.index')->with('status', __('Package copied successfully.'));
    }

    private function formOptions(): array
    {
        return [
            'categories' => PackageCategory::query()->orderBy('slug')->get(['id', 'slug', 'name'])->map(fn (PackageCategory $x) => ['id' => $x->id, 'label' => $x->labelForDefaultLanguage(), 'slug' => $x->slug])->values(),
            'labelGroups' => PackageLabelGroup::query()->orderBy('slug')->get(['id', 'slug', 'name'])->map(fn (PackageLabelGroup $x) => ['id' => $x->id, 'label' => $x->labelForDefaultLanguage(), 'slug' => $x->slug])->values(),
            'labels' => PackageLabel::query()->orderBy('slug')->get(['id', 'slug', 'name', 'package_label_group_id'])->map(fn (PackageLabel $x) => ['id' => $x->id, 'label' => $x->labelForDefaultLanguage(), 'slug' => $x->slug, 'group_id' => $x->package_label_group_id])->values(),
            'activities' => Activity::query()->orderBy('slug')->get(['id', 'slug', 'name'])->map(fn (Activity $x) => ['id' => $x->id, 'label' => $x->labelForDefaultLanguage(), 'slug' => $x->slug])->values(),
            'destinations' => Destination::query()->orderBy('slug')->get(['id', 'slug', 'name'])->map(fn (Destination $x) => ['id' => $x->id, 'label' => $x->labelForDefaultLanguage()])->values(),
            'hotels' => Hotel::query()->orderBy('slug')->get(['id', 'slug', 'name', 'destination_id'])->map(fn (Hotel $x) => ['id' => $x->id, 'label' => $x->labelForDefaultLanguage(), 'destination_id' => $x->destination_id])->values(),
            'boats' => Boat::query()->orderBy('slug')->get(['id', 'slug', 'name'])->map(fn (Boat $x) => ['id' => $x->id, 'label' => $x->labelForDefaultLanguage()])->values(),
            'rooms' => HotelRoom::query()->orderBy('slug')->get(['id', 'slug', 'name', 'hotel_id'])->map(fn (HotelRoom $x) => ['id' => $x->id, 'hotel_id' => $x->hotel_id, 'label' => $x->labelForDefaultLanguage()])->values(),
        ];
    }
}
