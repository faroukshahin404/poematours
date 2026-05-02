<?php

namespace App\Services\Frontend;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PackageSearchService
{
    /**
     * @param array<string, mixed> $filters
     * @return array{packages: Collection<int, array<string, mixed>>, galleryImages: array<int, string>, offers: array<int, string>}
     */
    public function search(array $filters): array
    {
        $packages = collect($this->packagesWithComputedFields())
            ->when(!empty($filters['destination']), function (Collection $collection) use ($filters): Collection {
                return $collection->filter(
                    fn (array $item): bool => strtolower($item['destination']) === strtolower((string) $filters['destination'])
                );
            })
            ->when(!empty($filters['duration']), function (Collection $collection) use ($filters): Collection {
                return $collection->filter(
                    fn (array $item): bool => (int) $item['duration_days'] <= (int) $filters['duration']
                );
            })
            ->when(!empty($filters['activity_types']), function (Collection $collection) use ($filters): Collection {
                $selected = collect((array) $filters['activity_types'])->map(
                    fn (string $type): string => strtolower($type)
                );

                return $collection->filter(function (array $item) use ($selected): bool {
                    $activities = collect($item['activities'])->map(fn (string $activity): string => strtolower($activity));
                    return $activities->intersect($selected)->isNotEmpty();
                });
            })
            ->when(!empty($filters['price_min']) || !empty($filters['price_max']), function (Collection $collection) use ($filters): Collection {
                $min = (int) ($filters['price_min'] ?? 0);
                $max = (int) ($filters['price_max'] ?? PHP_INT_MAX);

                return $collection->filter(fn (array $item): bool => $item['price_after'] >= $min && $item['price_after'] <= $max);
            })
            ->when(!empty($filters['q']), function (Collection $collection) use ($filters): Collection {
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
     * @param array<string, mixed> $package
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
     * @param string $activity
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
     * @param string $slug
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        return collect($this->packagesWithComputedFields())->first(
            fn (array $package): bool => $package['slug'] === $slug
        );
    }

    /**
     * @param string $slug
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function relatedPackages(string $slug, int $limit = 6): array
    {
        return collect($this->packagesWithComputedFields())
            ->reject(fn (array $package): bool => $package['slug'] === $slug)
            ->take($limit)
            ->values()
            ->all();
    }

    /**
     * @param array<string, mixed> $package
     * @param string $departureId
     * @return array<string, mixed>|null
     */
    public function findDepartureById(array $package, string $departureId): ?array
    {
        foreach ($package['details']['dates_prices']['months'] as $month => $monthData) {
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
     * @param array<string, mixed> $package
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
