<?php

namespace App\Services\Frontend;

use App\Models\Language;
use App\Models\PackageInclusion;
use App\Models\PackageReview;
use App\Models\TravelPackage;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PackageSearchService
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array{packages: Collection<int, array<string, mixed>>, galleryImages: array<int, string>, offers: array<int, string>}
     */
    public function search(array $filters): array
    {
        $packages = collect($this->packagesWithComputedFields())
            ->when(! empty($filters['destination']), function (Collection $collection) use ($filters): Collection {
                return $collection->filter(
                    fn (array $item): bool => strtolower($item['destination']) === strtolower((string) $filters['destination'])
                );
            })
            ->when(! empty($filters['duration']), function (Collection $collection) use ($filters): Collection {
                return $collection->filter(
                    fn (array $item): bool => (int) $item['duration_days'] <= (int) $filters['duration']
                );
            })
            ->when(! empty($filters['activity_types']), function (Collection $collection) use ($filters): Collection {
                $selected = collect((array) $filters['activity_types'])->map(
                    fn (string $type): string => strtolower($type)
                );

                return $collection->filter(function (array $item) use ($selected): bool {
                    $activities = collect($item['activities'])->map(fn (string $activity): string => strtolower($activity));

                    return $activities->intersect($selected)->isNotEmpty();
                });
            })
            ->when(! empty($filters['price_min']) || ! empty($filters['price_max']), function (Collection $collection) use ($filters): Collection {
                $min = (int) ($filters['price_min'] ?? 0);
                $max = (int) ($filters['price_max'] ?? PHP_INT_MAX);

                return $collection->filter(fn (array $item): bool => $item['price_after'] >= $min && $item['price_after'] <= $max);
            })
            ->when(! empty($filters['q']), function (Collection $collection) use ($filters): Collection {
                $needle = Str::lower(trim((string) $filters['q']));
                if ($needle === '') {
                    return $collection;
                }

                return $collection->filter(
                    fn (array $item): bool => $this->packageMatchesSearchQuery($item, $needle)
                );
            })
            ->values();

        return [
            'packages' => $packages,
            'galleryImages' => collect($this->galleryPool())->shuffle()->take(5)->values()->all(),
            'offers' => [
                'Save up to 20% on selected Nile departures this month.',
                'Complimentary airport transfer for all Luxor programs.',
                'Limited-time family package benefits in Cairo and Aswan.',
                'Exclusive private guide upgrades on premium departures.',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $package
     */
    private function packageMatchesSearchQuery(array $package, string $needle): bool
    {
        $haystack = Str::lower(implode(' ', array_filter([
            $package['title'] ?? '',
            $package['description'] ?? '',
            $package['destination'] ?? '',
            $package['group_name'] ?? '',
            implode(' ', $package['activities'] ?? []),
        ])));

        return Str::contains($haystack, $needle);
    }

    /**
     * @return array<int, string>
     */
    public function fullGalleryImages(): array
    {
        return $this->galleryPool();
    }

    /**
     * @return array<int, string>
     */
    public function activityTypes(): array
    {
        return collect($this->packages())
            ->pluck('activities')
            ->flatten()
            ->map(fn (string $activity): string => trim($activity))
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function packagesByActivity(string $activity): Collection
    {
        $normalizedActivity = strtolower(trim($activity));

        return collect($this->packagesWithComputedFields())
            ->filter(function (array $package) use ($normalizedActivity): bool {
                $packageActivities = collect($package['activities'])->map(
                    fn (string $item): string => strtolower($item)
                );

                return $packageActivities->contains($normalizedActivity);
            })
            ->values();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        $package = TravelPackage::query()
            ->with([
                'categories:id,name,slug',
                'labels:id,name,slug',
                'datePrices.accommodations:id,package_date_price_id,hotel_id,room_id',
                'datePrices.accommodations.hotel:id,name,description',
                'datePrices.accommodations.room:id,name',
                'itineraries:id,package_id,title,description,hotel_id,destination_id,sort_order',
                'itineraries.destination:id,name,lat,lng',
                'itineraries.hotel:id,name,description',
                'itineraries.hotel.media:id,path,model_type,model_id',
                'itineraries.boat:id,name',
                'packageReviews:id,package_id,reviewer_name,reviewer_address,comment,rate',
                'media:id,path,model_type,model_id',
                'extensions' => function ($query): void {
                    $query->orderByPivot('sort_order');
                },
                'extensions.media:id,path,model_type,model_id',
                'extensions.datePrices:id,package_id,price,offer',
                'extensions.datePrices.accommodations:id,package_date_price_id,hotel_id,room_id',
                'extensions.datePrices.accommodations.room:id,single_supplement',
                'extensions.itineraries:id,package_id,title,description,hotel_id,destination_id,sort_order',
                'extensions.itineraries.destination:id,name',
                'extensions.itineraries.hotel:id,name',
                'inclusions' => function ($query): void {
                    $query->orderBy('package_inclusions.id');
                },
            ])
            ->where('slug', $slug)
            ->first();

        if (! $package) {
            return null;
        }

        return $this->mapTravelPackageForDetails($package);
    }

    /**
     * Packages marked as featured in the admin (packages.featured = 1), shaped for listing/grid cards.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function featuredPackagesForCard(int $limit = 3): Collection
    {
        return TravelPackage::query()
            ->with([
                'categories:id,name,slug',
                'labels:id,name,slug',
                'datePrices:id,package_id,price,offer',
                'itineraries:id,package_id,destination_id,sort_order',
                'itineraries.destination:id,name',
                'media:id,path,model_type,model_id',
            ])
            ->where('featured', 1)
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->map(fn (TravelPackage $package): array => $this->mapTravelPackageForCard($package))
            ->values();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function relatedPackages(string $slug, int $limit = 6): array
    {
        return TravelPackage::query()
            ->with([
                'categories:id,name,slug',
                'labels:id,name,slug',
                'datePrices:id,package_id,price,offer',
                'itineraries:id,package_id,destination_id,sort_order',
                'itineraries.destination:id,name',
                'media:id,path,model_type,model_id',
            ])
            ->where('slug', '!=', $slug)
            ->limit($limit)
            ->get()
            ->map(fn (TravelPackage $package): array => $this->mapTravelPackageForCard($package))
            ->take($limit)
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $package
     * @return array<string, mixed>|null
     */
    public function findDepartureById(array $package, string $departureId): ?array
    {
        foreach (($package['details']['dates_prices']['months'] ?? []) as $month => $monthData) {
            foreach ($monthData['periods'] as $period) {
                if (($period['id'] ?? '') !== $departureId) {
                    continue;
                }

                return array_merge($period, [
                    'month' => $month,
                    'month_availability' => $monthData['availability'],
                ]);
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTravelPackageForCard(TravelPackage $package): array
    {
        $mediaPath = $package->media->first()?->path;
        $minFinalPrice = $package->datePrices->map(fn ($price) => $price->finalPrice())->min();
        $maxOriginalPrice = $package->datePrices->max('price');
        $destinationNames = $package->itineraries
            ->sortBy('sort_order')
            ->pluck('destination.name')
            ->filter()
            ->unique()
            ->values();

        return [
            'id' => $package->id,
            'slug' => $package->slug,
            'title' => $package->title,
            'description' => Str::limit(strip_tags((string) $package->description), 180),
            'duration_days' => max(1, $package->itineraries->count()),
            'price_before' => (float) ($maxOriginalPrice ?? 0),
            'price_after' => (float) ($minFinalPrice ?? 0),
            'image' => $mediaPath ? 'storage/'.$mediaPath : 'assets/images/placeholders/banner.jpeg',
            'destination' => (string) ($destinationNames->first() ?? ''),
            'itinerary_places' => $destinationNames->all(),
            'rating' => 4.8,
            'reviews_count' => 0,
            'has_offer' => $package->datePrices->contains(fn ($price): bool => (float) ($price->offer ?? 0) > 0),
            'category_names' => $package->categories->pluck('name')->filter()->values()->all(),
            'label_names' => $package->labels->pluck('name')->filter()->values()->all(),
            'label_slugs' => $package->labels->pluck('slug')->all(),
            'pdf_url' => $package->getRawOriginal('pdf') ? asset('storage/'.$package->getRawOriginal('pdf')) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTravelPackageForDetails(TravelPackage $package): array
    {
        $card = $this->mapTravelPackageForCard($package);
        $customDetails = $package->detailsData();
        $locale = session('locale', Language::defaultSlug());
        $defaultMedia = $card['image'] ?? 'assets/images/placeholders/banner.jpeg';

        $itineraryGroups = $this->buildItineraryGroups($package);
        $itineraryMapPoints = $this->buildItineraryMapPoints($itineraryGroups);
        $extensionPackages = $this->mapExtensionPackages($package, $locale);

        $months = $package->datePrices
            ->sortBy('from_date')
            ->groupBy(fn ($row): string => $this->formatDate((string) $row->from_date, 'F') ?: __('Unknown month'))
            ->map(function (Collection $rows): array {
                $first = $rows->first();

                return [
                    'from_price' => (float) (($rows->map(fn ($row) => $row->finalPrice())->min()) ?? 0),
                    'availability' => (int) ($rows->sum('available_seats')) > 0 ? __('Available') : __('Call for availability'),
                    'has_offer' => $rows->contains(fn ($row): bool => (float) ($row->offer ?? 0) > 0),
                    'periods' => $rows->map(function ($row): array {
                        $fromDate = $this->formatDate((string) $row->from_date, 'Y-m-d');
                        $toDate = $this->formatDate((string) $row->to_date, 'Y-m-d');
                        $hotelAccommodation = $row->accommodations->first();
                        $hotel = $hotelAccommodation?->hotel;
                        $room = $hotelAccommodation?->room;
                        $hotelImagePath = $hotel?->media?->first()?->path;

                        return [
                            'id' => (string) $row->id,
                            'period' => trim(($this->formatDate((string) $row->from_date, 'd M') ?: '').' - '.($this->formatDate((string) $row->to_date, 'd M') ?: '')),
                            'from_date' => $fromDate,
                            'to_date' => $toDate,
                            'price' => (float) $row->finalPrice(),
                            'available_spaces' => (int) $row->available_seats,
                            'availability' => (int) $row->available_seats > 0 ? __('Available') : __('Call for availability'),
                            'hotel_image' => $hotelImagePath ? 'storage/'.$hotelImagePath : 'assets/images/placeholders/banner.jpeg',
                            'hotel_description' => $this->translatedText(
                                $hotel?->descriptionTranslations() ?? [],
                                session('locale', Language::defaultSlug()),
                                __('Premium accommodation curated for this departure.')
                            ),
                            'single_supplement' => 0,
                            'cabin' => (string) ($room?->name ?? __('Standard Cabin')),
                        ];
                    })->values()->all(),
                    'month_meta' => $first ? $this->formatDate((string) $first->from_date, 'Y-m') : null,
                ];
            })
            ->all();

        $details = [
            'hero_image' => $defaultMedia,
            'count_destinations' => max(1, count($card['itinerary_places'] ?? [])),
            'max_guests' => 18,
            'available_places' => (int) ($package->datePrices->sum('available_seats')),
            'overview' => [
                'title' => $this->detailsLocalizedValue($customDetails, 'overview_title', $locale, (string) $package->title),
                'intro' => $this->detailsLocalizedValue($customDetails, 'overview_intro', $locale, (string) $package->description),
                'lead' => $this->detailsLocalizedValue($customDetails, 'overview_lead', $locale, (string) $package->description),
                'support' => $this->detailsLocalizedValue($customDetails, 'overview_support', $locale, ''),
                'highlights' => collect($customDetails['overview_highlights'] ?? [])
                    ->map(fn (mixed $item): string => $this->localizedRichTextFromMixed($item, $locale, ''))
                    ->filter(fn (string $item): bool => $item !== '')
                    ->values()
                    ->all(),
                'gallery' => $package->media->take(4)->map(fn ($media) => 'storage/'.$media->path)->values()->all(),
            ],
            'itinerary' => $itineraryGroups,
            'itinerary_map_points' => $itineraryMapPoints,
            'extensions' => $extensionPackages,
            'ship' => [
                'name' => $this->detailsLocalizedValue($customDetails, 'ship_name', $locale, __('Journey Vessel')),
                'description' => $this->detailsLocalizedValue($customDetails, 'ship_description', $locale, __('Experience curated luxury service throughout your journey.')),
                'image' => $package->media->get(1)?->path ? 'storage/'.$package->media->get(1)->path : $defaultMedia,
                'gallery' => $package->media->take(3)->map(fn ($media) => 'storage/'.$media->path)->values()->all(),
            ],
            'essential_info' => collect($customDetails['essential_info'] ?? [])->values()->all(),
            'reviews' => $this->buildDisplayReviews($package, $customDetails),
            'map_image' => trim((string) ($customDetails['map_image'] ?? '')) ?: 'assets/images/placeholders/map.avif',
            'dates_prices' => [
                'offer_cards' => collect($customDetails['offer_cards'] ?? [])->values()->all(),
                'inclusions' => $this->buildDatesPricesInclusionsDisplay($package, $customDetails, $locale),
                'notes' => collect($customDetails['notes'] ?? [__('Contact our team for complete departure inclusions')])->values()->all(),
                'months' => $months,
            ],
            'labels' => [
                'overview' => __('Overview'),
                'itinerary' => __('Itinerary'),
                'ship' => __('About the ship'),
                'dates_prices' => __('Dates & Prices'),
                'essential_info' => __('Essential Info'),
                'reviews' => __('Reviews'),
            ],
        ];

        if ($details['overview']['gallery'] === []) {
            $details['overview']['gallery'] = [$defaultMedia];
        }
        if ($details['ship']['gallery'] === []) {
            $details['ship']['gallery'] = [$details['ship']['image']];
        }

        $reviews = $details['reviews'];
        $reviewsCount = count($reviews);
        $avgRating = $reviewsCount > 0
            ? round(array_sum(array_column($reviews, 'rate')) / $reviewsCount, 1)
            : (float) $card['rating'];

        return array_merge($card, [
            'details' => $details,
            'reviews_count' => $reviewsCount,
            'rating' => $avgRating,
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildItineraryGroups(TravelPackage $package): array
    {
        $orderedItineraries = $package->itineraries->sortBy('sort_order')->values();
        $grouped = [];
        $lastDestinationKey = null;
        $locale = session('locale', Language::defaultSlug());

        foreach ($orderedItineraries as $row) {
            $destinationName = (string) ($row->destination?->name ?? __('Unknown destination'));
            $destinationKey = (string) ($row->destination_id ?? $destinationName);

            $hotel = $row->hotel;
            $hotelGallery = $hotel?->media?->map(fn ($media) => 'storage/'.$media->path)->values()->all() ?? [];

            $dayPayload = [
                'title' => (string) $row->title,
                'description' => (string) ($row->description ?? ''),
                'hotel' => (string) ($hotel?->name ?? __('Selected hotel')),
                'hotel_description' => $this->translatedText(
                    $hotel?->descriptionTranslations() ?? [],
                    $locale,
                    __('A curated luxury stay selected for this departure.')
                ),
                'hotel_gallery' => $hotelGallery !== [] ? $hotelGallery : ['assets/images/placeholders/banner.jpeg'],
            ];

            if ($lastDestinationKey !== $destinationKey) {
                $grouped[] = [
                    'destination' => $destinationName,
                    'lat' => $row->destination?->lat !== null ? (float) $row->destination->lat : null,
                    'lng' => $row->destination?->lng !== null ? (float) $row->destination->lng : null,
                    'map_index' => count($grouped),
                    'days' => [],
                ];
            }

            $lastGroupIndex = array_key_last($grouped);
            if ($lastGroupIndex !== null) {
                $grouped[$lastGroupIndex]['days'][] = $dayPayload;
            }

            $lastDestinationKey = $destinationKey;
        }

        return collect($grouped)
            ->map(fn (array $group): array => [
                'destination' => $group['destination'],
                'lat' => $group['lat'],
                'lng' => $group['lng'],
                'map_index' => $group['map_index'],
                'days' => collect($group['days'])->values()->all(),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $itineraryGroups
     * @return array<int, array<string, mixed>>
     */
    private function buildItineraryMapPoints(array $itineraryGroups): array
    {
        return collect($itineraryGroups)
            ->filter(fn (array $group): bool => $group['lat'] !== null && $group['lng'] !== null)
            ->map(fn (array $group): array => [
                'name' => $group['destination'],
                'lat' => (float) $group['lat'],
                'lng' => (float) $group['lng'],
                'index' => (int) $group['map_index'],
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function mapExtensionPackages(TravelPackage $package, string $locale): array
    {
        if (! $package->relationLoaded('extensions')) {
            return [];
        }

        return $package->extensions
            ->map(function (TravelPackage $extension) use ($locale): array {
                $mediaPath = $extension->media->first()?->path;
                $minPrice = $extension->datePrices->map(fn ($price) => $price->finalPrice())->min();
                $singleSupplement = $extension->datePrices
                    ->flatMap(fn ($price) => $price->accommodations)
                    ->map(fn ($acc) => (float) ($acc->room?->single_supplement ?? 0))
                    ->max() ?? 0;
                $durationDays = max(1, $extension->itineraries->count());
                $type = (string) ($extension->pivot->type ?? 'pre_tour');
                $typeLabel = $type === 'post_tour' ? __('Post-Tour Extension') : __('Pre-Tour Extension');
                $descriptionText = strip_tags($this->translatedText(
                    $extension->descriptionTranslations(),
                    $locale,
                    ''
                ));

                return [
                    'id' => $extension->id,
                    'slug' => $extension->slug,
                    'title' => (string) $extension->title,
                    'type' => $type,
                    'type_label' => $typeLabel,
                    'duration_days' => $durationDays,
                    'duration_badge' => '+'. $durationDays.' '.strtoupper(__('Days')),
                    'description' => Str::limit($descriptionText, 220),
                    'description_full' => $descriptionText,
                    'image' => $mediaPath ? 'storage/'.$mediaPath : 'assets/images/placeholders/banner.jpeg',
                    'price_per_person' => (float) ($minPrice ?? 0),
                    'single_supplement' => (float) $singleSupplement,
                    'inclusions_text' => trim((string) ($extension->pivot->inclusions_text ?? '')),
                    'itinerary' => $this->buildItineraryGroups($extension),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Package-level inclusions from the database (icon + localized label), with legacy JSON string fallback.
     *
     * @return array<int, array{label: string, icon: ?string}>
     */
    private function buildDatesPricesInclusionsDisplay(TravelPackage $package, array $customDetails, string $locale): array
    {
        $relation = $package->relationLoaded('inclusions')
            ? $package->inclusions
            : $package->inclusions()->orderBy('package_inclusions.id')->get();

        $fromDb = $relation
            ->map(function (PackageInclusion $inc) use ($locale): array {
                $translations = $inc->nameTranslations();
                $label = trim((string) ($translations[$locale] ?? ''));
                if ($label === '') {
                    $label = trim((string) ($translations[Language::defaultSlug()] ?? ''));
                }
                if ($label === '' && $translations !== []) {
                    $label = trim((string) reset($translations));
                }

                $iconRaw = $inc->icon;
                $icon = ($iconRaw !== null && $iconRaw !== '') ? trim((string) $iconRaw) : null;

                return [
                    'label' => $label,
                    'icon' => $icon,
                ];
            })
            ->filter(fn (array $row): bool => $row['label'] !== '')
            ->values()
            ->all();

        if ($fromDb !== []) {
            return $fromDb;
        }

        return collect($customDetails['inclusions'] ?? [__('Curated itinerary and expert-guided experiences')])
            ->map(fn (mixed $item): array => [
                'label' => is_string($item) ? trim($item) : trim((string) $item),
                'icon' => null,
            ])
            ->filter(fn (array $row): bool => $row['label'] !== '')
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $customDetails
     * @return array<int, array<string, mixed>>
     */
    private function buildDisplayReviews(TravelPackage $package, array $customDetails): array
    {
        $rows = $package->relationLoaded('packageReviews')
            ? $package->packageReviews
            : $package->packageReviews()->orderByDesc('id')->get();

        if ($rows->isNotEmpty()) {
            return $rows
                ->sortByDesc('id')
                ->values()
                ->map(function (PackageReview $r): array {
                    return [
                        'id' => $r->id,
                        'reviewer_name' => $r->reviewer_name,
                        'reviewer_address' => (string) ($r->reviewer_address ?? ''),
                        'comment' => $r->comment,
                        'rate' => (int) $r->rate,
                    ];
                })
                ->all();
        }

        return collect($customDetails['reviews'] ?? [])
            ->values()
            ->map(function (mixed $item, int $index): array {
                $row = is_array($item) ? $item : [];

                return [
                    'id' => 'legacy-'.$index,
                    'reviewer_name' => (string) ($row['reviewer_name'] ?? $row['name'] ?? ''),
                    'reviewer_address' => (string) ($row['reviewer_address'] ?? ''),
                    'comment' => (string) ($row['comment'] ?? ''),
                    'rate' => (int) ($row['rate'] ?? 5),
                    'month_added' => (string) ($row['month_added'] ?? ''),
                ];
            })
            ->all();
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function detailsLocalizedValue(array $details, string $key, string $locale, string $fallback): string
    {

        $value = $details[$key] ?? [];

        $map = is_array($value)
            ? $value
            : $this->normalizeTranslationMapForRichText($value);

        if ($map === []) {
            return trim($fallback);
        }

        return $this->translatedText($map, $locale, $fallback);
    }

    /**
     * Resolve multilingual rich text from mixed stored shapes (arrays, JSON maps, nested JSON strings).
     */
    private function localizedRichTextFromMixed(mixed $value, string $locale, string $fallback): string
    {
        $map = $this->normalizeTranslationMapForRichText($value);
        if ($map === []) {
            return trim($fallback);
        }

        return $this->translatedText($map, $locale, $fallback);
    }

    /**
     * @param  array<string, mixed>  $translations
     */
    private function translatedText(array $translations, string $locale, string $fallback): string
    {
        $text = $this->pickTranslation($translations, $locale, $fallback);
        $text = $this->unwrapNestedLocalizedContent($text, $locale, $fallback, 0);

        return $this->sanitizeTrustedRichText($text);
    }

    /**
     * @param  array<string, mixed>  $translations
     */
    private function pickTranslation(array $translations, string $locale, string $fallback): string
    {
        $legacyPlain = null;
        if (array_key_exists('__legacy_plain', $translations)) {
            $legacyPlain = trim((string) $translations['__legacy_plain']);
            unset($translations['__legacy_plain']);
        }

        $normalized = [];
        foreach ($translations as $key => $value) {
            $normalized[strtolower((string) $key)] = trim((string) $value);
        }

        foreach ($this->localeLookupKeys($locale) as $candidate) {
            $lookup = strtolower($candidate);
            if (($normalized[$lookup] ?? '') !== '') {
                return $normalized[$lookup];
            }
        }

        $first = collect($normalized)->first(fn (string $value): bool => $value !== '');
        if ($first !== null && $first !== '') {
            return $first;
        }

        if ($legacyPlain !== null && $legacyPlain !== '') {
            return $legacyPlain;
        }

        return trim($fallback);
    }

    /**
     * @return array<int, string>
     */
    private function localeLookupKeys(string $locale): array
    {
        $locale = trim($locale);
        $keys = array_unique(array_filter([
            $locale,
            strtolower($locale),
            Str::slug($locale),
        ], fn (string $v): bool => $v !== ''));

        $canonical = strtolower($locale);
        $aliasGroups = [
            ['en', 'english'],
            ['ar', 'arabic'],
            ['fr', 'french'],
            ['de', 'german'],
            ['es', 'spanish'],
            ['it', 'italian'],
            ['pt', 'portuguese'],
            ['ru', 'russian'],
            ['zh', 'chinese'],
            ['ja', 'japanese'],
            ['ko', 'korean'],
        ];

        foreach ($aliasGroups as $group) {
            $lowerGroup = array_map('strtolower', $group);
            if (in_array($canonical, $lowerGroup, true)) {
                foreach ($group as $alias) {
                    $keys[] = $alias;
                    $keys[] = strtolower($alias);
                }

                break;
            }
        }

        return array_values(array_unique(array_filter($keys, fn (string $v): bool => $v !== '')));
    }

    private function unwrapNestedLocalizedContent(string $text, string $locale, string $fallback, int $depth): string
    {
        if ($depth >= 3) {
            return $text;
        }

        $trimmed = trim($text);
        if ($trimmed === '' || ! str_starts_with($trimmed, '{')) {
            return $text;
        }

        $decoded = json_decode($trimmed, true);
        if (! is_array($decoded) || ! $this->looksLikeTranslationMap($decoded)) {
            return $text;
        }

        $inner = $this->pickTranslation($decoded, $locale, '');
        if ($inner === '' || $inner === $trimmed) {
            return $text;
        }

        return $this->unwrapNestedLocalizedContent($inner, $locale, $fallback, $depth + 1);
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeTranslationMapForRichText(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $str = trim((string) $value);
        if ($str === '') {
            return [];
        }

        if (str_starts_with($str, '{')) {
            $decoded = json_decode($str, true);
            if (is_array($decoded) && $this->looksLikeTranslationMap($decoded)) {
                return $decoded;
            }
        }

        return ['__legacy_plain' => $str];
    }

    /**
     * @param  array<mixed, mixed>  $decoded
     */
    private function looksLikeTranslationMap(array $decoded): bool
    {
        if ($decoded === []) {
            return false;
        }

        foreach ($decoded as $key => $value) {
            if (! is_string($key) || ! preg_match('/^[a-z][a-z0-9_-]*$/i', $key)) {
                return false;
            }

            if (is_array($value) || is_object($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Strip risky markup while preserving common editorial HTML from the CMS.
     */
    private function sanitizeTrustedRichText(string $html): string
    {
        $allowed = '<p><br><br/><b><strong><i><em><u><span><div><ul><ol><li>'
            .'<h1><h2><h3><h4><h5><h6><blockquote><cite><small><sup><sub>';

        return strip_tags($html, $allowed);
    }

    private function formatDate(string $value, string $format): ?string
    {
        if (trim($value) === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format($format);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function packagesWithComputedFields(): array
    {
        return collect($this->packages())->map(function (array $package): array {
            $package['slug'] = Str::slug($package['title']);
            $package['details'] = $this->packageDetails($package);

            return $package;
        })->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function packages(): array
    {
        return [
            [
                'title' => 'Classic Cairo and Giza Escape',
                'description' => 'Explore the Egyptian Museum, Old Cairo, and the timeless Giza Plateau.',
                'duration_days' => 5,
                'group_name' => 'Cultural Discovery',
                'price_before' => 2100,
                'price_after' => 1780,
                'rating' => 4.8,
                'reviews_count' => 126,
                'destination' => 'Cairo',
                'activities' => ['Culture', 'Family'],
                'image' => 'assets/images/placeholders/pyramids.avif',
            ],
            [
                'title' => 'Luxor Temples and Nile Serenity',
                'description' => 'Witness Karnak and Luxor temples with a refined stay by the Nile.',
                'duration_days' => 6,
                'group_name' => 'Heritage Journeys',
                'price_before' => 2400,
                'price_after' => 1990,
                'rating' => 4.9,
                'reviews_count' => 94,
                'destination' => 'Luxor',
                'activities' => ['Culture', 'Family', 'Cycling'],
                'image' => 'assets/images/placeholders/nile-1.avif',
            ],
            [
                'title' => 'Aswan Sun and Island Retreat',
                'description' => 'Relax between Nubian culture, river cruises, and curated local experiences.',
                'duration_days' => 4,
                'group_name' => 'Leisure Collection',
                'price_before' => 1800,
                'price_after' => 1490,
                'rating' => 4.7,
                'reviews_count' => 71,
                'destination' => 'Aswan',
                'activities' => ['Family', 'Culture'],
                'image' => 'assets/images/placeholders/sea-4.webp',
            ],
            [
                'title' => 'Egypt Grand Highlights',
                'description' => 'A complete itinerary linking Cairo, Luxor, and Aswan in one luxury route.',
                'duration_days' => 9,
                'group_name' => 'Signature Collection',
                'price_before' => 3600,
                'price_after' => 2990,
                'rating' => 4.9,
                'reviews_count' => 152,
                'destination' => 'Cairo',
                'activities' => ['Culture', 'Family', 'Polar'],
                'image' => 'assets/images/placeholders/template-1.jpeg',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $package
     * @return array<string, mixed>
     */
    private function packageDetails(array $package): array
    {
        return [
            'hero_image' => $package['image'],
            'count_destinations' => 3,
            'max_guests' => 18,
            'available_places' => 6,
            'overview' => [
                'text' => 'This journey blends timeless heritage, carefully paced days, and elevated hospitality. From sunrise over temple columns to private-guided cultural visits and refined dining experiences, each moment is designed to balance discovery and comfort with a distinctly luxurious rhythm.',
                'gallery' => collect($this->galleryPool())->shuffle()->take(4)->values()->all(),
            ],
            'itinerary' => [
                [
                    'destination' => 'Cairo',
                    'days' => [
                        ['title' => 'Day 1: Arrival and City Welcome', 'description' => 'Private transfer and evening orientation with panoramic city views.', 'hotel' => 'Nile Palace Cairo'],
                        ['title' => 'Day 2: Pyramids and Museum Highlights', 'description' => 'Guided exploration of Giza Plateau and curated museum access.', 'hotel' => 'Nile Palace Cairo'],
                    ],
                ],
                [
                    'destination' => 'Luxor',
                    'days' => [
                        ['title' => 'Day 3: East Bank Discovery', 'description' => 'Immersive temple visit with expert historian-led storytelling.', 'hotel' => 'Luxor Riverside Lodge'],
                        ['title' => 'Day 4: Valley and Artisan Quarter', 'description' => 'Morning heritage walk followed by local crafts experience.', 'hotel' => 'Luxor Riverside Lodge'],
                    ],
                ],
                [
                    'destination' => 'Aswan',
                    'days' => [
                        ['title' => 'Day 5: Nubian Encounters', 'description' => 'Boat crossing and curated cultural visit to authentic villages.', 'hotel' => 'Aswan Serenity Resort'],
                    ],
                ],
                [
                    'destination' => 'Cairo',
                    'days' => [
                        ['title' => 'Day 6: Arrival and City Welcome', 'description' => 'Private transfer and evening orientation with panoramic city views.', 'hotel' => 'Nile Palace Cairo'],
                        ['title' => 'Day 7: Pyramids and Museum Highlights', 'description' => 'Guided exploration of Giza Plateau and curated museum access.', 'hotel' => 'Nile Palace Cairo'],
                    ],
                ],
                [
                    'destination' => 'Luxor',
                    'days' => [
                        ['title' => 'Day 8: East Bank Discovery', 'description' => 'Immersive temple visit with expert historian-led storytelling.', 'hotel' => 'Luxor Riverside Lodge'],
                        ['title' => 'Day 9: Valley and Artisan Quarter', 'description' => 'Morning heritage walk followed by local crafts experience.', 'hotel' => 'Luxor Riverside Lodge'],
                    ],
                ],
                [
                    'destination' => 'Aswan',
                    'days' => [
                        ['title' => 'Day 10: Nubian Encounters', 'description' => 'Boat crossing and curated cultural visit to authentic villages.', 'hotel' => 'Aswan Serenity Resort'],
                    ],
                ],
            ],
            'ship' => [
                'name' => 'Sanctuary Zein Nile Vessel',
                'description' => 'A boutique river vessel featuring panoramic suites, fine dining, and elegant lounge spaces with personalized service.',
                'image' => 'assets/images/placeholders/nile-2.jpeg',
                'gallery' => [
                    'assets/images/placeholders/nile-2.jpeg',
                    'assets/images/placeholders/nile-3.jpg',
                    'assets/images/placeholders/nile-4.webp',
                ],
            ],
            'essential_info' => [
                [
                    'question' => 'Is This Trip for You?',
                    'answer' => 'This journey is ideal for travelers who enjoy cultural depth, structured touring days, and refined accommodation. It is suitable for couples, small private groups, and families with older children.',
                ],
                [
                    'question' => 'Joining Instructions',
                    'answer' => 'A detailed joining pack is shared before departure with flight windows, transfer contact points, and arrival assistance details for each city on your itinerary.',
                ],
                [
                    'question' => 'Vaccinations and Health',
                    'answer' => 'There are no mandatory vaccination requirements in most cases; however, tetanus, typhoid, and hepatitis A vaccinations are generally recommended. We advise consulting your travel clinic before departure.',
                ],
                [
                    'question' => 'Visa',
                    'answer' => 'Most travelers can obtain an Egypt tourist visa on arrival or via e-visa, depending on nationality. Please verify embassy guidance for your passport prior to travel.',
                ],
                [
                    'question' => 'Food & Drink',
                    'answer' => 'Daily breakfast and selected dinners are included. We recommend bottled water for drinking and brushing teeth throughout the trip.',
                ],
                [
                    'question' => 'Weather',
                    'answer' => 'Egypt has warm to hot days with cooler evenings in winter months. Light breathable layers and sun protection are recommended year-round.',
                ],
                [
                    'question' => 'Sustainability and Impact',
                    'answer' => 'Your journey supports local guides, regional suppliers, and heritage-led experiences curated to promote responsible tourism and community benefit.',
                ],
            ],
            'reviews' => [
                ['name' => 'Emily Carter', 'month_added' => 'January 2026', 'rate' => 5, 'comment' => 'A beautifully curated journey with outstanding guides and flawless logistics from start to finish.'],
                ['name' => 'Liam Hassan', 'month_added' => 'February 2026', 'rate' => 4, 'comment' => 'Excellent itinerary pacing and premium hotels. The Nile cruise segment was especially memorable.'],
                ['name' => 'Sophia Reed', 'month_added' => 'March 2026', 'rate' => 5, 'comment' => 'The archaeological visits felt exclusive and deeply informative. Highly recommended for culture lovers.'],
                ['name' => 'Omar Khaled', 'month_added' => 'April 2026', 'rate' => 4, 'comment' => 'Very strong organization and service quality. Would book another departure with this team.'],
                ['name' => 'Maya Johnson', 'month_added' => 'May 2026', 'rate' => 5, 'comment' => 'Perfect balance of comfort, history, and immersive local experiences throughout the route.'],
                ['name' => 'Noah Stevens', 'month_added' => 'June 2026', 'rate' => 4, 'comment' => 'Loved the itinerary design and hotel standards. The expert Egyptologist made a huge difference.'],
            ],
            'dates_prices' => [
                'offer_cards' => [
                    [
                        'title' => 'Offer',
                        'description' => 'Save 50% on the single supplement on select departures.',
                        'link_label' => 'Learn more',
                    ],
                    [
                        'title' => 'Offer',
                        'description' => 'Save $500 per person on select departures booked by June 30, 2026.',
                        'link_label' => 'Learn more',
                    ],
                ],
                'inclusions' => [
                    'Traveling Bell Boy luggage handling',
                    'English-speaking resident tour director and local guides',
                    'Airport meet and greet with private transfers',
                    'Traveler valet laundry service',
                    'Internet access',
                    'Entrance fees, taxes, and gratuities except resident tour director',
                ],
                'notes' => [
                    '24/7 A&K on-call support during the full itinerary',
                    'Optional pre-tour extension available on this departure',
                ],
                'months' => [
                    'January' => [
                        'from_price' => 2780,
                        'availability' => 'Call for availability',
                        'has_offer' => false,
                        'periods' => [
                            ['id' => 'jan-10-15', 'period' => '10 Jan - 15 Jan', 'from_date' => '2026-01-10', 'to_date' => '2026-01-15', 'price' => 2780, 'available_spaces' => 7, 'availability' => 'Call for availability', 'hotel_image' => 'assets/images/placeholders/template-2.avif', 'hotel_description' => 'Premium river-facing suite with curated welcome amenities.', 'single_supplement' => 460, 'cabin' => 'Upper Deck Suite'],
                            ['id' => 'jan-21-26', 'period' => '21 Jan - 26 Jan', 'from_date' => '2026-01-21', 'to_date' => '2026-01-26', 'price' => 2890, 'available_spaces' => 4, 'availability' => 'Limited availability', 'hotel_image' => 'assets/images/placeholders/sea-2.jpg', 'hotel_description' => 'Signature corner suite with panoramic city access.', 'single_supplement' => 520, 'cabin' => 'Signature Balcony Cabin'],
                        ],
                    ],
                    'June' => [
                        'from_price' => 10295,
                        'availability' => 'Limited availability',
                        'has_offer' => true,
                        'periods' => [
                            ['id' => 'jun-06-15', 'period' => '06 Jun - 15 Jun', 'from_date' => '2026-06-06', 'to_date' => '2026-06-15', 'price' => 10295, 'available_spaces' => 3, 'availability' => 'Call for availability', 'hotel_image' => 'assets/images/placeholders/nile-3.jpg', 'hotel_description' => 'Refined suite combining privacy and premium lounge services.', 'single_supplement' => 570, 'cabin' => 'Royal Panorama Cabin'],
                            ['id' => 'jun-30-jul-09', 'period' => '30 Jun - 09 Jul', 'from_date' => '2026-06-30', 'to_date' => '2026-07-09', 'price' => 13795, 'available_spaces' => 2, 'availability' => 'Limited availability', 'hotel_image' => 'assets/images/placeholders/nile-4.webp', 'hotel_description' => 'Elegant panorama suite with elevated butler service.', 'single_supplement' => 610, 'cabin' => 'Panorama Upper Deck'],
                        ],
                    ],
                    'July' => [
                        'from_price' => 10495,
                        'availability' => 'Available',
                        'has_offer' => false,
                        'periods' => [
                            ['id' => 'jul-12-21', 'period' => '12 Jul - 21 Jul', 'from_date' => '2026-07-12', 'to_date' => '2026-07-21', 'price' => 10495, 'available_spaces' => 8, 'availability' => 'Available', 'hotel_image' => 'assets/images/placeholders/sea-1.jpg', 'hotel_description' => 'Contemporary suite with warm neutral luxury tones.', 'single_supplement' => 490, 'cabin' => 'Signature Deluxe'],
                        ],
                    ],
                    'August' => [
                        'from_price' => 11795,
                        'availability' => 'Available',
                        'has_offer' => true,
                        'periods' => [
                            ['id' => 'aug-08-17', 'period' => '08 Aug - 17 Aug', 'from_date' => '2026-08-08', 'to_date' => '2026-08-17', 'price' => 11795, 'available_spaces' => 6, 'availability' => 'Available', 'hotel_image' => 'assets/images/placeholders/sea-5.jpg', 'hotel_description' => 'Spacious suite curated for summer departures.', 'single_supplement' => 530, 'cabin' => 'Royal Deluxe Suite'],
                        ],
                    ],
                    'September' => [
                        'from_price' => 11295,
                        'availability' => 'Available',
                        'has_offer' => true,
                        'periods' => [
                            ['id' => 'sep-13-22', 'period' => '13 Sep - 22 Sep', 'from_date' => '2026-09-13', 'to_date' => '2026-09-22', 'price' => 11295, 'available_spaces' => 9, 'availability' => 'Available', 'hotel_image' => 'assets/images/placeholders/pyramids.avif', 'hotel_description' => 'Signature heritage suite with private lounge access.', 'single_supplement' => 540, 'cabin' => 'Heritage Collection Cabin'],
                        ],
                    ],
                    'October' => [
                        'from_price' => 13795,
                        'availability' => 'Available',
                        'has_offer' => true,
                        'periods' => [
                            ['id' => 'oct-10-19', 'period' => '10 Oct - 19 Oct', 'from_date' => '2026-10-10', 'to_date' => '2026-10-19', 'price' => 13795, 'available_spaces' => 5, 'availability' => 'Available', 'hotel_image' => 'assets/images/placeholders/template-1.jpeg', 'hotel_description' => 'Premier suite with panoramic river-front perspective.', 'single_supplement' => 680, 'cabin' => 'Premier Sanctuary Suite'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    private function galleryPool(): array
    {
        return [
            'assets/images/placeholders/banner.jpeg',
            'assets/images/placeholders/nile-1.avif',
            'assets/images/placeholders/nile-2.jpeg',
            'assets/images/placeholders/nile-3.jpg',
            'assets/images/placeholders/nile-4.webp',
            'assets/images/placeholders/pyramids.avif',
            'assets/images/placeholders/sea-1.jpg',
            'assets/images/placeholders/sea-2.jpg',
            'assets/images/placeholders/sea-3.avif',
            'assets/images/placeholders/sea-4.webp',
            'assets/images/placeholders/sea-5.jpg',
            'assets/images/placeholders/template-1.jpeg',
            'assets/images/placeholders/template-2.avif',
        ];
    }
}
