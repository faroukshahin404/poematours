<?php

namespace App\View\Composers;

use App\Services\Frontend\FrontendPageSeoService;
use Illuminate\View\View;

class FrontendLayoutSeoComposer
{
    /**
     * Route names backed by a `pages.slug` row for CMS SEO.
     *
     * @var array<string, string>
     */
    private const ROUTE_PAGE_SLUGS = [
        'home' => 'home',
        'about.us' => 'about-us',
        'destinations.index' => 'destinations',
        'our.journeys' => 'our-journeys',
        'terms.of.use' => 'terms-and-conditions',
        'privacy.policy' => 'privacy-policy',
        'login' => 'login',
        'register' => 'register',
        'password.request' => 'forgot-password',
        'packages.index' => 'packages',
        'packages.gallery' => 'packages',
        'search' => 'search',
        'activities.show' => 'activities',
        'customize.create' => 'customize',
        'reservation.create' => 'reservation',
        'payment.success' => 'payment-success',
        'payment.failure' => 'payment-failure',
    ];

    public function __construct(
        private readonly FrontendPageSeoService $frontendPageSeoService
    ) {}

    public function compose(View $view): void
    {
        $existing = $view->getData()['pageSeo'] ?? null;
        if (is_array($existing) && $existing !== []) {
            return;
        }

        $routeName = request()->route()?->getName();
        $slug = $routeName !== null ? (self::ROUTE_PAGE_SLUGS[$routeName] ?? null) : null;

        $pageSeo = $slug !== null
            ? $this->frontendPageSeoService->fromSlug($slug)
            : $this->frontendPageSeoService->defaults();

        if (($pageSeo['og_url'] ?? null) === null) {
            $pageSeo['og_url'] = request()->url();
        }

        $view->with('pageSeo', $pageSeo);
    }
}
