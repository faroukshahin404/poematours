<?php

namespace App\Http\Controllers\FronEnd;

use App\Contracts\Repositories\Front\PackageRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Services\Frontend\PackageSearchService;
use App\Services\Frontend\ReelViewService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct(
        private readonly PackageSearchService $packageSearchService,
        private readonly PackageRepositoryInterface $packageRepository,
        private readonly ReelViewService $reelViewService
    )
    {
    }

    public function index(Request $request): View
    {
        $view = $request->string('view')->toString() ?: 'grid';
        if (! in_array($view, ['list', 'grid', 'map'], true)) {
            $view = 'grid';
        }

        $selectedDestinationSlug = $request->string('destination')->toString();
        $selectedDestination = $this->packageRepository->findDestinationBySlug($selectedDestinationSlug);
        $selectedCategorySlug = $request->string('category')->toString();
        $selectedCategory = $this->packageRepository->findCategoryBySlug($selectedCategorySlug);
        $categoryIds = collect((array) $request->input('category_ids', []))
            ->map(fn (mixed $id): int => (int) $id)
            ->filter()
            ->values();

        if ($selectedCategory !== null && ! $categoryIds->contains($selectedCategory->id)) {
            $categoryIds->push((int) $selectedCategory->id);
        }

        $filters = [
            'destination' => $selectedDestinationSlug,
            'category_ids' => $categoryIds->all(),
            'label_ids' => collect((array) $request->input('label_ids', []))->map(fn (mixed $id): int => (int) $id)->filter()->values()->all(),
            'from_date' => $request->string('from_date')->toString(),
            'to_date' => $request->string('to_date')->toString(),
            'adults' => $request->integer('adults', 2),
            'kids' => $request->integer('kids', 0),
            'duration' => $request->integer('duration', 0),
            'activity_types' => $request->input('activity_types', []),
            'price_min' => $request->integer('price_min', 0),
            'price_max' => $request->integer('price_max', 0),
            'view' => $view,
        ];
    
        $packages = collect($this->packageRepository->listPackages($filters));

        $recommendedPackages = $packages->where('recommended', 1)->values();
        if ($recommendedPackages->isEmpty()) {
            $recommendedPackages = $packages;
        }

        $destinations = $this->packageRepository->listDestinations();
        $accommodations = $this->packageRepository->listAccommodations($selectedDestination?->id);
        $placesToVisit = $this->packageRepository->listPlacesToVisit($selectedDestination?->id);
        $waysToExplore = $this->packageRepository->listWaysToExplore($selectedDestination?->id);
        $categories = $this->packageRepository->listCategories();
        $labelGroups = $this->packageRepository->listLabelGroups();
        $priceBounds = $this->packageRepository->priceBounds();

        $galleryImages = $packages->pluck('image')->filter()->values()->all();
        if ($galleryImages === []) {
            $galleryImages = ['assets/images/placeholders/banner.jpeg'];
        }
        
        return view('frontend.packages.index', [
            'filters' => $filters,
            'packages' => $packages,
            'recommendedPackages' => $recommendedPackages,
            'galleryImages' => $galleryImages,
            'offers' => [],
            'destinations' => $destinations,
            'accommodations' => $accommodations,
            'placesToVisit' => $placesToVisit,
            'waysToExplore' => $waysToExplore,
            'categories' => $categories,
            'labelGroups' => $labelGroups,
            'priceBounds' => $priceBounds,
            'selectedDestination' => $selectedDestination,
        ]);
    }


    public function search(Request $request): View
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
            'q' => $request->string('q')->toString(),
        ];

        $searchResults = $this->packageSearchService->search($filters);

        return view('frontend.search.index', [
            'filters' => $filters,
            'packages' => $searchResults['packages'],
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
        $activityModel = $this->packageRepository->findActivityBySlug($activity);
        if (! $activityModel) {
            return redirect()->route('packages.index');
        }

        $activityName = (string) $activityModel->name;
        $activityFilterName = $activityModel->labelForDefaultLanguage() ?: $activityName;
        $activityTrips = $this->packageSearchService->packagesByActivity($activityFilterName);
        $heroImage = $activityTrips->first()['image'] ?? 'assets/images/placeholders/banner.jpeg';
        $labels = $this->packageRepository->activitiesLabels($activityName);
        $descriptionTranslations = $activityModel->descriptionTranslations();
        $locale = session('locale', \App\Models\Language::defaultSlug());
        $activityDescription = $descriptionTranslations[$locale] ?? reset($descriptionTranslations) ?: '';

        return view('frontend.activities.show', [
            'activityName' => $activityName,
            'heroImage' => $heroImage,
            'activityLabels' => $labels,
            'activityDescription' => $activityDescription,
            'activityCards' => $this->packageRepository->allActivityCards(),
            'activityTrips' => $activityTrips,
            'reels' => $this->reelViewService->random(),
        ]);
    }

    public function show(string $slug): View|RedirectResponse
    {


        $package = $this->packageSearchService->findBySlug($slug);

        if (!$package) {
            return redirect()->route('packages.index');
        }

        $availableYears = collect($package['details']['dates_prices']['months'] ?? [])
            ->pluck('month_meta')
            ->filter()
            ->map(fn (string $meta): int => (int) explode('-', $meta)[0])
            ->unique()
            ->sort()
            ->values();
        $defaultYear = (int) ($availableYears->first() ?? date('Y'));

        return view('frontend.package-details.index', [
            'package' => $package,
            'details' => $package['details'],
            'selectedYear' => (int) request('year', $defaultYear),
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
