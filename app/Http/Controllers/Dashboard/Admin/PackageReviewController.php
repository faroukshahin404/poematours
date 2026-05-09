<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\StorePackageReviewRequest;
use App\Http\Requests\Dashboard\Admin\UpdatePackageReviewRequest;
use App\Models\PackageReview;
use App\Models\TravelPackage;
use App\Services\Dashboard\Admin\PackageReviewService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PackageReviewController extends Controller
{
    public function __construct(
        private readonly PackageReviewService $packageReviews
    ) {}

    public function index(TravelPackage $package): Response
    {
        $package->load(['packageReviews' => fn ($q) => $q->orderByDesc('id')]);

        return Inertia::render('Dashboard/Admin/Packages/PackageReviews', [
            'package' => [
                'id' => $package->id,
                'title' => $package->title,
                'slug' => $package->slug,
            ],
            'reviews' => $package->packageReviews->map(fn (PackageReview $r): array => [
                'id' => $r->id,
                'reviewer_name' => $r->reviewer_name,
                'reviewer_address' => $r->reviewer_address,
                'comment' => $r->comment,
                'rate' => $r->rate,
                'package_id' => $r->package_id,
            ])->values(),
        ]);
    }

    public function store(StorePackageReviewRequest $request, TravelPackage $package): RedirectResponse
    {
        $this->packageReviews->create($package, $request->reviewPayload());

        return redirect()
            ->route('admin.packages.package-reviews.index', $package)
            ->with('status', __('Review created successfully.'));
    }

    public function update(
        UpdatePackageReviewRequest $request,
        TravelPackage $package,
        PackageReview $package_review
    ): RedirectResponse {
        $this->assertReviewBelongsToPackage($package, $package_review);

        $this->packageReviews->update($package_review, $request->reviewPayload());

        return redirect()
            ->route('admin.packages.package-reviews.index', $package)
            ->with('status', __('Review updated successfully.'));
    }

    public function destroy(TravelPackage $package, PackageReview $package_review): RedirectResponse
    {
        $this->assertReviewBelongsToPackage($package, $package_review);

        $this->packageReviews->delete($package_review);

        return redirect()
            ->route('admin.packages.package-reviews.index', $package)
            ->with('status', __('Review deleted successfully.'));
    }

    private function assertReviewBelongsToPackage(TravelPackage $package, PackageReview $review): void
    {
        abort_unless($review->package_id === $package->id, 404);
    }
}
