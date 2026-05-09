<?php

namespace App\Http\Controllers\FronEnd;

use App\Contracts\Repositories\Front\PackageRepositoryInterface;
use App\Contracts\Repositories\Front\ReservationRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\ReservationQuestion;
use App\Services\Frontend\FrontendPageSeoService;
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
        private readonly ReelViewService $reelViewService,
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly FrontendPageSeoService $frontendPageSeoService,
    ) {}

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
            'trip_type' => $this->normalizedTripType($request),
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
            'mapDestinations' => $this->mapDestinationsForPackagesPage($destinations, $filters),
        ]);
    }

    public function search(Request $request): View
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
            'label_ids' => collect((array) $request->input('label_ids', []))
                ->map(fn (mixed $id): int => (int) $id)
                ->filter()
                ->values()
                ->all(),
            'from_date' => $request->string('from_date')->toString(),
            'to_date' => $request->string('to_date')->toString(),
            'adults' => $request->integer('adults', 2),
            'kids' => $request->integer('kids', 0),
            'duration' => $request->integer('duration', 0),
            'price_min' => $request->integer('price_min', 0),
            'price_max' => $request->integer('price_max', 0),
            'view' => $view,
            'q' => $request->string('q')->toString(),
            'trip_type' => $this->normalizedTripType($request),
        ];

        $packages = collect($this->packageRepository->listPackages($filters));

        $destinations = $this->packageRepository->listDestinations();
        $categories = $this->packageRepository->listCategories();
        $labelGroups = $this->packageRepository->listLabelGroups();
        $priceBounds = $this->packageRepository->priceBounds();

        return view('frontend.search.index', [
            'filters' => $filters,
            'packages' => $packages,
            'filtersFormAction' => route('search'),
            'destinations' => $destinations,
            'categories' => $categories,
            'labelGroups' => $labelGroups,
            'priceBounds' => $priceBounds,
            'selectedDestination' => $selectedDestination,
        ]);
    }

    private function normalizedTripType(Request $request): string
    {
        $tripType = $request->string('trip_type')->toString();

        return in_array($tripType, ['private', 'small-group'], true) ? $tripType : '';
    }

    /**
     * @param  array<int, array<string, mixed>>  $destinations
     * @param  array<string, mixed>  $filters
     * @return array<int, array{name: string, slug: string, lat: float, lng: float, url: string}>
     */
    private function mapDestinationsForPackagesPage(array $destinations, array $filters): array
    {
        $tripType = (string) ($filters['trip_type'] ?? '');
        $includeTripType = in_array($tripType, ['private', 'small-group'], true);

        return collect($destinations)
            ->filter(fn ($destination): bool => isset($destination['lat'], $destination['lng'])
                && $destination['lat'] !== null
                && $destination['lng'] !== null)
            ->map(function (array $destination) use ($includeTripType, $tripType): array {
                $query = ['destination' => $destination['slug']];
                if ($includeTripType) {
                    $query['trip_type'] = $tripType;
                }

                return [
                    'name' => $destination['name'],
                    'slug' => $destination['slug'],
                    'lat' => (float) $destination['lat'],
                    'lng' => (float) $destination['lng'],
                    'url' => route('packages.index', $query),
                ];
            })
            ->values()
            ->all();
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
        $locale = session('locale', Language::defaultSlug());
        $activityDescription = $descriptionTranslations[$locale] ?? reset($descriptionTranslations) ?: '';

        $activitiesSeo = $this->frontendPageSeoService->fromSlug('activities');
        $pageSeo = [
            ...$activitiesSeo,
            'title' => "{$activityName} — ".$activitiesSeo['title'],
            'og_title' => "{$activityName} — ".$activitiesSeo['og_title'],
            'canonical_url' => url()->current(),
            'og_url' => url()->current(),
            'og_image' => $this->frontendPageSeoService->absoluteAssetUrl($heroImage),
        ];

        return view('frontend.activities.show', [
            'activityName' => $activityName,
            'heroImage' => $heroImage,
            'activityLabels' => $labels,
            'activityDescription' => $activityDescription,
            'activityCards' => $this->packageRepository->allActivityCards(),
            'activityTrips' => $activityTrips,
            'reels' => $this->reelViewService->random(),
            'pageSeo' => $pageSeo,
        ]);
    }

    public function show(string $slug): View|RedirectResponse
    {

        $package = $this->packageSearchService->findBySlug($slug);

        if (! $package) {
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
            'pageSeo' => $this->frontendPageSeoService->forTravelPackage($package, 'packages.show'),
        ]);
    }

    public function reviews(string $slug): View|RedirectResponse
    {
        $package = $this->packageSearchService->findBySlug($slug);
        if (! $package) {
            return redirect()->route('packages.index');
        }

        $pageSeo = $this->frontendPageSeoService->forTravelPackage($package, 'packages.reviews');
        $packageTitle = (string) ($package['title'] ?? '');
        $pageSeo['title'] = $packageTitle !== ''
            ? "{$packageTitle} — Reviews | Poema Tours"
            : 'Reviews | Poema Tours';

        $availableYears = collect($package['details']['dates_prices']['months'] ?? [])
            ->pluck('month_meta')
            ->filter()
            ->map(fn (string $meta): int => (int) explode('-', $meta)[0])
            ->unique()
            ->sort()
            ->values();
        $defaultYear = (int) ($availableYears->first() ?? date('Y'));

        return view('frontend.package-details.reviews', [
            'package' => $package,
            'reviews' => $package['details']['reviews'],
            'selectedYear' => (int) request('year', $defaultYear),
            'pageSeo' => $pageSeo,
        ]);
    }

    public function book(string $slug): View|RedirectResponse
    {
        $package = $this->packageSearchService->findBySlug($slug);
        if (! $package) {
            return redirect()->route('packages.index');
        }

        $departureId = request()->string('departure_id')->toString();
        $selectedDeparture = $departureId
            ? $this->packageSearchService->findDepartureById($package, $departureId)
            : null;

        $adultsCount = max(1, (int) request('adults', 2));
        $locale = (string) session('locale', Language::defaultSlug());
        $dynamicQuestions = collect($this->reservationRepository->packageReservationQuestions())
            ->map(function (ReservationQuestion $question) use ($locale): array {
                $titleTranslations = $question->titleTranslations();
                $descriptionTranslations = $question->descriptionTranslations();

                return [
                    'id' => $question->id,
                    'title' => $titleTranslations[$locale] ?? reset($titleTranslations) ?: '',
                    'description' => $descriptionTranslations[$locale] ?? reset($descriptionTranslations) ?: '',
                    'type' => $question->type,
                    'options' => collect($question->normalizedOptions())
                        ->map(function (array $option) use ($locale): array {
                            $labelTranslations = $option['label'] ?? [];

                            return [
                                'label' => $labelTranslations[$locale] ?? reset($labelTranslations) ?: '',
                                'added_price' => (float) ($option['added_price'] ?? 0),
                            ];
                        })
                        ->values()
                        ->all(),
                ];
            })
            ->values()
            ->all();

        $pageSeo = $this->frontendPageSeoService->forTravelPackage($package, 'packages.book');
        $packageTitle = (string) ($package['title'] ?? '');
        $pageSeo['title'] = $packageTitle !== ''
            ? "Book {$packageTitle} | Poema Tours"
            : 'Book a Tour | Poema Tours';

        return view('frontend.package-details.booking', [
            'package' => $package,
            'details' => $package['details'],
            'departure' => $selectedDeparture,
            'adultsCount' => $adultsCount,
            'dynamicQuestions' => $dynamicQuestions,
            'pageSeo' => $pageSeo,
        ]);
    }
}
