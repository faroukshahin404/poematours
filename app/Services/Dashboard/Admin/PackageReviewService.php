<?php

namespace App\Services\Dashboard\Admin;

use App\Models\PackageReview;
use App\Models\TravelPackage;

class PackageReviewService
{
    /**
     * @param  array{reviewer_name: string, reviewer_address: ?string, comment: string, rate: int}  $data
     */
    public function create(TravelPackage $package, array $data): PackageReview
    {
        return $package->packageReviews()->create($data);
    }

    /**
     * @param  array{reviewer_name: string, reviewer_address: ?string, comment: string, rate: int}  $data
     */
    public function update(PackageReview $review, array $data): void
    {
        $review->update($data);
    }

    public function delete(PackageReview $review): void
    {
        $review->delete();
    }
}
