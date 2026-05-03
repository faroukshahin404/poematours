<?php

namespace App\Contracts\Repositories\Front;

use App\Models\Activity;
use App\Models\Destination;
use App\Models\PackageCategory;

interface PackageRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $filters
     * @return array<int, array<string, mixed>>
     */
    public function listPackages(array $filters): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listDestinations(): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAccommodations(?int $destinationId): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listCategories(): array;

    /**
     * @return array<int, array{slug: string, label: string, eyebrow: string, title: string, description: string, cta: string, image: string, url: string}>
     */
    public function listWaysToExplore(?int $destinationId): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listLabelGroups(): array;

    /**
     * @return array<int, array{title: string, description: string, image: string}>
     */
    public function listPlacesToVisit(?int $destinationId): array;

    /**
     * @return array{min: int, max: int}
     */
    public function priceBounds(): array;

    public function findDestinationBySlug(string $slug): ?Destination;
    public function findCategoryBySlug(string $slug): ?PackageCategory;

    public function findActivityBySlug(string $slug): ?Activity;

    /**
     * @return array<string, mixed>
     */
    public function activitiesLabels(string $activityName): array;

    /**
     * @return array<int, array{name: string, image: string, link: string}>
     */
    public function allActivityCards(): array;
}
