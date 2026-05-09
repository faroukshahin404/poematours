<?php

namespace App\Repositories\Front;

use App\Contracts\Repositories\Front\PackageRepositoryInterface;
use App\Models\Activity;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\Language;
use App\Models\PackageCategory;
use App\Models\PackageDatePrice;
use App\Models\PackageLabelGroup;
use App\Models\Page;
use App\Models\TravelPackage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PackageRepository implements PackageRepositoryInterface
{
    public function listPackages(array $filters): array
    {
        $packagesQuery = TravelPackage::query()
            ->with([
                'categories:id,name,slug',
                'labels:id,name,slug,package_label_group_id',
                'datePrices:id,package_id,from_date,to_date,price,offer',
                'itineraries:id,package_id,destination_id,sort_order',
                'itineraries.destination:id,name,slug,image',
                'media:id,path,model_type,model_id',
            ]);

        $this->applyPackageFilters($packagesQuery, $filters);

        return $packagesQuery
            ->get()
            ->map(fn (TravelPackage $package): array => $this->mapPackageCardData($package))
            ->values()
            ->all();
    }

    public function listDestinations(): array
    {
        return Destination::query()
            ->orderBy('slug')
            ->get(['id', 'name', 'slug', 'image', 'lat', 'lng'])
            ->map(fn (Destination $destination): array => [
                'id' => $destination->id,
                'slug' => $destination->slug,
                'name' => $destination->name,
                'location' => $destination->name.', Egypt',
                'title' => $destination->name,
                'lat' => $destination->lat !== null ? (float) $destination->lat : null,
                'lng' => $destination->lng !== null ? (float) $destination->lng : null,
                'image' => $destination->getRawOriginal('image')
                    ? 'storage/'.$destination->getRawOriginal('image')
                    : 'assets/images/placeholders/banner.jpeg',
            ])
            ->values()
            ->all();
    }

    public function listAccommodations(?int $destinationId): array
    {
        return Hotel::query()
            ->when($destinationId !== null, fn (Builder $query) => $query->where('destination_id', $destinationId))
            ->with([
                'destination:id,name,slug',
                'media:id,path,model_type,model_id',
            ])
            ->orderBy('slug')
            ->get(['id', 'name', 'slug', 'destination_id'])
            ->map(function (Hotel $hotel): array {
                $mediaPath = $hotel->media->first()?->path;
                $image = $mediaPath ? 'storage/'.$mediaPath : 'assets/images/placeholders/banner.jpeg';

                return [
                    'location' => $hotel->destination?->name ? $hotel->destination->name.', Egypt' : 'Egypt',
                    'title' => $hotel->name,
                    'image' => $image,
                ];
            })
            ->values()
            ->all();
    }

    public function listCategories(): array
    {
        return PackageCategory::query()
            ->orderBy('slug')
            ->get(['id', 'name', 'slug'])
            ->map(fn (PackageCategory $category): array => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
            ])
            ->values()
            ->all();
    }

    public function listWaysToExplore(?int $destinationId): array
    {
        $locale = session('locale', Language::defaultSlug());

        return PackageCategory::query()

            ->orderBy('slug')
            ->get(['id', 'name', 'title', 'description', 'slug', 'image'])
            ->map(function (PackageCategory $category) use ($locale): array {
                $title = $this->translatedValue($category->titleTranslations(), $locale, '');
                $description = $this->translatedValue($category->descriptionTranslations(), $locale, '');
                $rawImage = $category->getRawOriginal('image');
                $name = $category->name;

                return [
                    'slug' => $category->slug,
                    'label' => (string) $name,
                    'eyebrow' => (string) $name,
                    'title' => $title !== '' ? $title : (string) $name,
                    'description' => $description !== '' ? $description : 'Discover curated journeys in this category.',
                    'cta' => 'Explore '.(string) $name,
                    'image' => $rawImage ? 'storage/'.$rawImage : 'assets/images/placeholders/banner.jpeg',
                    'url' => route('packages.index', ['category' => $category->slug]),
                ];
            })
            ->values()
            ->all();
    }

    public function listLabelGroups(): array
    {
        return PackageLabelGroup::query()
            ->with(['labels:id,name,slug,package_label_group_id'])
            ->orderBy('slug')
            ->get(['id', 'name', 'slug'])
            ->map(fn (PackageLabelGroup $group): array => [
                'id' => $group->id,
                'name' => $group->name,
                'slug' => $group->slug,
                'labels' => $group->labels
                    ->sortBy('slug')
                    ->values()
                    ->map(fn ($label): array => [
                        'id' => $label->id,
                        'name' => $label->name,
                        'slug' => $label->slug,
                    ])
                    ->all(),
            ])
            ->values()
            ->all();
    }

    public function listPlacesToVisit(?int $destinationId): array
    {
        $locale = session('locale', Language::defaultSlug());

        return Activity::query()
            ->when($destinationId !== null, fn (Builder $query) => $query->where('destination_id', $destinationId))
            ->orderBy('slug')
            ->get(['name', 'description', 'image'])
            ->map(function (Activity $activity) use ($locale): array {
                $description = $this->translatedValue($activity->descriptionTranslations(), $locale, '');
                $rawImage = $activity->getRawOriginal('image');

                return [
                    'title' => $activity->name,
                    'description' => Str::limit(strip_tags($description), 160),
                    'image' => $rawImage ? 'storage/'.$rawImage : 'assets/images/placeholders/banner.jpeg',
                ];
            })
            ->values()
            ->all();
    }

    public function priceBounds(): array
    {
        $priceRange = PackageDatePrice::query()
            ->selectRaw('MIN(GREATEST(price - COALESCE(offer, 0), 0)) as min_price')
            ->selectRaw('MAX(GREATEST(price - COALESCE(offer, 0), 0)) as max_price')
            ->first();

        return [
            'min' => (int) ($priceRange?->min_price ?? 0),
            'max' => (int) ($priceRange?->max_price ?? 5000),
        ];
    }

    public function findDestinationBySlug(string $slug): ?Destination
    {
        if ($slug === '') {
            return null;
        }

        return Destination::query()->where('slug', $slug)->first();
    }

    public function findCategoryBySlug(string $slug): ?PackageCategory
    {
        if ($slug === '') {
            return null;
        }

        return PackageCategory::query()->where('slug', $slug)->first();
    }

    public function findActivityBySlug(string $slug): ?Activity
    {
        return Activity::query()->where('slug', $slug)->first();
    }

    public function activitiesLabels(string $activityName): array
    {
        $content = Page::query()
            ->where('slug', 'activities')
            ->first()?->sections()
            ->where('key', 'activities_labels')
            ->where('is_active', true)
            ->first()?->content ?? [];

        $defaults = [
            'hero_title_suffix' => 'Activities',
            'hero_view_journeys_label' => 'View All Journeys',
            'breadcrumb_home_label' => 'Home',
            'breadcrumb_packages_label' => 'Packages',
            'hero_side_title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt.',
            'hero_side_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.',
            'intro_title_template' => ':activity in Egypt',
            'trips_title_template' => ':activity Trips',
            'empty_trips_label' => 'No trips are available for this activity yet.',
            'activities_list_title' => 'All Activities',
        ];

        $labels = array_merge($defaults, is_array($content) ? $content : []);
        $labels['intro_title'] = str_replace(':activity', $activityName, (string) $labels['intro_title_template']);
        $labels['trips_title'] = str_replace(':activity', $activityName, (string) $labels['trips_title_template']);

        return $labels;
    }

    public function allActivityCards(): array
    {
        return Activity::query()
            ->orderBy('slug')
            ->get(['name', 'slug', 'image'])
            ->map(fn (Activity $item): array => [
                'name' => $item->name,
                'image' => $item->getRawOriginal('image')
                    ? 'storage/'.$item->getRawOriginal('image')
                    : 'assets/images/placeholders/banner.jpeg',
                'link' => route('activities.show', ['activity' => $item->slug]),
            ])
            ->values()
            ->all();
    }

    /**
     * @param  Builder<TravelPackage>  $query
     * @param  array<string, mixed>  $filters
     */
    private function applyPackageFilters(Builder $query, array $filters): void
    {
        $destinationSlug = (string) ($filters['destination'] ?? '');
        $query->when($destinationSlug !== '', function (Builder $builder) use ($destinationSlug): void {
            $builder->whereHas('itineraries.destination', fn (Builder $q) => $q->where('slug', $destinationSlug));
        });

        $tripType = (string) ($filters['trip_type'] ?? '');
        if ($tripType === 'private') {
            $query->where('is_private', 1);
        } elseif ($tripType === 'small-group') {
            $query->where('is_small_group', 1);
        }

        $query->when(($filters['category_ids'] ?? []) !== [], function (Builder $builder) use ($filters): void {
            $builder->whereHas('categories', fn (Builder $q) => $q->whereIn('package_categories.id', $filters['category_ids']));
        });

        $query->when(($filters['label_ids'] ?? []) !== [], function (Builder $builder) use ($filters): void {
            $builder->whereHas('labels', fn (Builder $q) => $q->whereIn('package_labels.id', $filters['label_ids']));
        });

        $maxDurationDays = (int) ($filters['duration'] ?? 0);
        if ($maxDurationDays > 0) {
            $query->whereRaw(
                '(SELECT COUNT(*) FROM itineraries WHERE itineraries.package_id = packages.id) <= ?',
                [$maxDurationDays]
            );
        }

        $fromDate = (string) ($filters['from_date'] ?? '');
        $toDate = (string) ($filters['to_date'] ?? '');
        if ($fromDate !== '' || $toDate !== '') {
            $query->whereHas('datePrices', function (Builder $dateQuery) use ($fromDate, $toDate): void {
                if ($fromDate !== '') {
                    $dateQuery->whereDate('to_date', '>=', $fromDate);
                }
                if ($toDate !== '') {
                    $dateQuery->whereDate('from_date', '<=', $toDate);
                }
            });
        }

        $priceMin = (int) ($filters['price_min'] ?? 0);
        $priceMax = (int) ($filters['price_max'] ?? 0);
        if ($priceMin > 0 || $priceMax > 0) {
            $query->whereHas('datePrices', function (Builder $dateQuery) use ($priceMin, $priceMax): void {
                if ($priceMin > 0) {
                    $dateQuery->whereRaw('GREATEST(price - COALESCE(offer, 0), 0) >= ?', [$priceMin]);
                }
                if ($priceMax > 0) {
                    $dateQuery->whereRaw('GREATEST(price - COALESCE(offer, 0), 0) <= ?', [$priceMax]);
                }
            });
        }

        $searchQuery = trim((string) ($filters['q'] ?? ''));
        if ($searchQuery !== '') {
            $tokens = array_values(array_filter(array_map('trim', preg_split('/\s+/u', $searchQuery))));
            foreach ($tokens as $token) {
                $pattern = '%'.addcslashes($token, '%_\\').'%';
                $query->where(function (Builder $inner) use ($pattern): void {
                    $inner->where('packages.slug', 'like', $pattern)
                        ->orWhere('packages.title', 'like', $pattern);
                });
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function mapPackageCardData(TravelPackage $package): array
    {
        $locale = session('locale', Language::defaultSlug());
        $mediaPath = $package->media->first()?->path;
        $minFinalPrice = $package->datePrices->map(fn ($price) => $price->finalPrice())->min();
        $hasOffer = $package->datePrices->contains(fn ($price): bool => (float) ($price->offer ?? 0) > 0);
        $destinationNames = $package->itineraries
            ->sortBy('sort_order')
            ->pluck('destination.name')
            ->filter()
            ->unique()
            ->values();
        $description = $this->translatedValue($package->descriptionTranslations(), $locale, (string) $package->description);

        return [
            'slug' => $package->slug,
            'title' => $package->title,
            'description' => Str::limit(strip_tags($description), 180),
            'duration_days' => max(1, $package->itineraries->count()),
            'price_after' => (float) ($minFinalPrice ?? 0),
            'image' => $mediaPath ? 'storage/'.$mediaPath : 'assets/images/placeholders/banner.jpeg',
            'destination' => (string) ($destinationNames->first() ?? ''),
            'itinerary_places' => $destinationNames->all(),
            'rating' => 4.8,
            'reviews_count' => 0,
            'has_offer' => $hasOffer,
            'recommended' => $package->recommended,
            'featured' => $package->featured,
            'category_names' => $package->categories->pluck('name')->filter()->values()->all(),
            'label_names' => $package->labels->pluck('name')->filter()->values()->all(),
            'label_slugs' => $package->labels->pluck('slug')->all(),
        ];
    }

    /**
     * @param  array<string, string>  $translations
     */
    private function translatedValue(array $translations, string $locale, string $fallback): string
    {
        if (isset($translations[$locale]) && trim((string) $translations[$locale]) !== '') {
            return trim((string) $translations[$locale]);
        }

        $first = collect($translations)
            ->map(fn (mixed $value): string => trim((string) $value))
            ->first(fn (string $value): bool => $value !== '');

        return $first ?: $fallback;
    }
}
