<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Services\Frontend\PackageSearchService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function __construct(private readonly PackageSearchService $packageSearchService)
    {
    }

    public function index(Request $request): View
    {
        $view = $request->string('view')->toString() ?: 'grid';
        if (!in_array($view, ['list', 'grid', 'map'], true)) {
            $view = 'grid';
        }

        $filters = [
            'destination' => $request->string('destination')->toString(),
            'travel_date' => $request->string('travel_date')->toString(),
            'adults' => $request->integer('adults', 2),
            'kids' => $request->integer('kids', 0),
            'duration' => $request->integer('duration', 0),
            'activity_types' => $request->input('activity_types', []),
            'price_min' => $request->integer('price_min', 0),
            'price_max' => $request->integer('price_max', 0),
            'view' => $view,
        ];

        $searchResults = $this->packageSearchService->search($filters);

        return view('frontend.packages.index', [
            'filters' => $filters,
            'packages' => $searchResults['packages'],
            'galleryImages' => $searchResults['galleryImages'],
            'offers' => $searchResults['offers'],
        ]);
    }

    public function gallery(): View
    {
        return view('frontend.packages.gallery', [
            'galleryImages' => $this->packageSearchService->fullGalleryImages(),
        ]);
    }

    public function activities(string $activity): View|RedirectResponse
    {
        $activityName = Str::of($activity)->replace('-', ' ')->title()->toString();
        $availableActivities = $this->packageSearchService->activityTypes();

        if (!collect($availableActivities)->contains($activityName)) {
            return redirect()->route('packages.index');
        }

        $activityTrips = $this->packageSearchService->packagesByActivity($activityName);
        $heroImage = $activityTrips->first()['image'] ?? 'assets/images/placeholders/banner.jpeg';

        return view('frontend.activities.show', [
            'activityName' => $activityName,
            'heroImage' => $heroImage,
            'activityDescription' => sprintf(
                'Discover %s experiences across Egypt with curated routes, expert guidance, and premium stays built around your travel style.',
                $activityName
            ),
            'activityTrips' => $activityTrips,
            'seasonCards' => [
                ['name' => 'Spring', 'image' => 'assets/images/placeholders/pyramids.avif'],
                ['name' => 'Summer', 'image' => 'assets/images/placeholders/sea-2.jpg'],
                ['name' => 'Autumn', 'image' => 'assets/images/placeholders/nile-2.jpeg'],
                ['name' => 'Winter', 'image' => 'assets/images/placeholders/template-1.jpeg'],
            ],
        ]);
    }

    public function show(string $slug): View|RedirectResponse
    {
        $package = $this->packageSearchService->findBySlug($slug);
        if (!$package) {
            return redirect()->route('packages.index');
        }

        return view('frontend.package-details.index', [
            'package' => $package,
            'details' => $package['details'],
            'selectedYear' => (int) request('year', 2026),
            'relatedPackages' => $this->packageSearchService->relatedPackages($slug),
            'selectedAdults' => max(1, (int) request('adults', 2)),
        ]);
    }

    public function reviews(string $slug): View|RedirectResponse
    {
        $package = $this->packageSearchService->findBySlug($slug);
        if (!$package) {
            return redirect()->route('packages.index');
        }

        return view('frontend.package-details.reviews', [
            'package' => $package,
            'reviews' => $package['details']['reviews'],
        ]);
    }

    public function book(string $slug): View|RedirectResponse
    {
        $package = $this->packageSearchService->findBySlug($slug);
        if (!$package) {
            return redirect()->route('packages.index');
        }

        $departureId = request()->string('departure_id')->toString();
        $selectedDeparture = $departureId
            ? $this->packageSearchService->findDepartureById($package, $departureId)
            : null;

        $adultsCount = max(1, (int) request('adults', 2));

        return view('frontend.package-details.booking', [
            'package' => $package,
            'details' => $package['details'],
            'departure' => $selectedDeparture,
            'adultsCount' => $adultsCount,
        ]);
    }
}
