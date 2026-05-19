<?php

namespace App\Http\Controllers\FronEnd;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Language;
use App\Models\Page;
use App\Services\Frontend\DestinationViewService;
use App\Services\Frontend\EgyptDestinationsService;
use App\Services\Frontend\FrontendPageSeoService;
use App\Services\Frontend\JourneyBlogService;
use App\Services\Frontend\PackageSearchService;
use App\Services\Frontend\ReelViewService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function __construct(
        private readonly FrontendPageSeoService $frontendPageSeoService
    ) {}

    public function index(): View
    {
        $journeyHighlights = array_slice(app(JourneyBlogService::class)->all(), 0, 4);
        $lastMinutePackages = app(PackageSearchService::class)->featuredPackagesForCard(3);
        $homeContent = $this->homePageContent();
        $destinations = app(DestinationViewService::class)->all();

        return view('frontend.home.index', [
            'journeyHighlights' => $journeyHighlights,
            'lastMinutePackages' => $lastMinutePackages,
            'toursAcrossEgyptActivities' => $this->randomActivitiesForHome(9),
            'homeDestinations' => $destinations,
            'homeStoryBlogs' => app(JourneyBlogService::class)->randomFeatured(3),
            'homeHero' => $homeContent['homeHero'],
            'homeSpirit' => $homeContent['homeSpirit'],
            'homeToursAcrossEgypt' => $homeContent['homeToursAcrossEgypt'],
            'homeLastMinute' => $homeContent['homeLastMinute'],
            'homeStories' => $homeContent['homeStories'],
            'homeWhyPoema' => $homeContent['homeWhyPoema'],
            'homeGoogleReviews' => $homeContent['homeGoogleReviews'],
        ]);
    }

    public function aboutUs(): View
    {
        return view('frontend.pages.aboutus.index', [
            ...$this->aboutUsContent(),
            'homeGoogleReviews' => $this->homeGoogleReviewsContent(),
            'reels' => app(ReelViewService::class)->random(),
        ]);
    }

    public function destinations(): View
    {
        return view('frontend.destinations.index', [
            'destinations' => app(EgyptDestinationsService::class)->galleryItems(),
        ]);
    }

    public function ourJourneys(): View
    {
        $blogs = app(JourneyBlogService::class)->all();
        $featuredBlog = collect($blogs)->first(fn (array $blog): bool => (bool) ($blog['is_featured'] ?? false))
            ?? ($blogs[0] ?? null);

        $categories = collect($blogs)
            ->map(fn (array $blog): string => (string) ($blog['category'] ?? ''))
            ->filter(fn (string $category): bool => $category !== '')
            ->unique()
            ->values()
            ->all();

        return view('frontend.our-journies.index', [
            'featuredBlog' => $featuredBlog,
            'journeyBlogs' => collect($blogs)
                ->values()
                ->all(),
            'categories' => array_values(array_merge(['All'], $categories)),
        ]);
    }

    public function ourJourneyDetails(string $slug): View
    {
        $blog = app(JourneyBlogService::class)->findBySlug($slug);
        abort_if(! $blog, 404);

        return view('frontend.our-journies.details', [
            'blog' => $blog,
            'pageSeo' => $this->frontendPageSeoService->forJourneyBlogPost($blog),
        ]);
    }

    public function termsOfUse(): View
    {
        return view('frontend.pages.legal.terms-of-use', [
            'legal' => $this->legalPageContent('terms-and-conditions', 'legal_terms_of_use'),
        ]);
    }

    public function privacyPolicy(): View
    {
        return view('frontend.pages.legal.privacy-policy', [
            'legal' => $this->legalPageContent('privacy-policy', 'legal_privacy_policy'),
        ]);
    }

    public function dynamicPage(Page $page): View
    {
        $body = $page->body;
        abort_if(! is_string($body) || trim($body) === '', 404);

        return view('frontend.pages.dynamic.show', [
            'cmsPage' => $page,
            'pageSeo' => $this->frontendPageSeoService->fromPage($page),
        ]);
    }

    public function login(): View
    {
        return view('frontend.auth.login');
    }

    public function register(): View
    {
        return view('frontend.auth.register');
    }

    public function forgotPassword(): View
    {
        return view('frontend.auth.forgot-password');
    }

    /**
     * @return array<string, mixed>
     */
    private function aboutUsContent(): array
    {
        $sections = Page::query()
            ->where('slug', 'about-us')
            ->first()?->sections()
            ->where('is_active', true)
            ->get()
            ->keyBy('key');

        $hero = $sections?->get('about_hero')?->content ?? [];
        $welcome = $sections?->get('about_welcome')?->content ?? [];
        $services = $sections?->get('about_services')?->content ?? [];
        $gallery = $sections?->get('about_gallery')?->content ?? [];
        $latestBlogs = $sections?->get('about_latest_blogs')?->content ?? [];

        return [
            'aboutHero' => array_merge([
                'breadcrumb_home_label' => 'Home',
                'breadcrumb_current_label' => 'About Us',
                'title' => 'Poema Tours Company Profile',
                'subtitle' => 'Crafting elevated travel experiences across Egypt with heritage, care, and detail.',
            ], is_array($hero) ? $hero : []),
            'aboutWelcome' => array_merge([
                'title' => 'Welcome to Poema Tours',
                'paragraphs' => [],
                'image' => 'assets/images/placeholders/team.avif',
                'image_alt' => 'Poema Tours team',
            ], is_array($welcome) ? $welcome : []),
            'aboutServices' => array_merge([
                'title' => 'Our Services',
                'items' => [],
            ], is_array($services) ? $services : []),
            'aboutGallery' => array_merge([
                'title' => 'Gallery',
                'images' => [],
            ], is_array($gallery) ? $gallery : []),
            'aboutLatestBlogs' => array_merge([
                'title' => 'Latest Blogs',
                'items' => [],
            ], is_array($latestBlogs) ? $latestBlogs : []),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function legalPageContent(string $pageSlug, string $sectionKey): array
    {
        $locale = session('locale', Language::defaultSlug());
        $defaultTitle = match ($sectionKey) {
            'legal_terms_of_use' => 'Terms of Use',
            'legal_privacy_policy' => 'Privacy Policy',
            default => 'Legal',
        };

        $page = Page::query()->where('slug', $pageSlug)->first();

        if ($page !== null && is_string($page->body) && trim($page->body) !== '') {
            $sectionRow = $page->sections()->where('key', $sectionKey)->first();
            $sectionContent = is_array($sectionRow?->content) ? $sectionRow->content : [];

            return [
                'breadcrumb_home_label' => $this->translatedValue($sectionContent['breadcrumb_home_label_translations'] ?? [], $locale, 'Home'),
                'breadcrumb_current_label' => $this->translatedValue(
                    $sectionContent['breadcrumb_current_label_translations'] ?? [],
                    $locale,
                    $page->name
                ),
                'title' => $this->translatedValue($sectionContent['title_translations'] ?? [], $locale, $page->name),
                'body' => $page->body,
                'contact_email' => (string) ($sectionContent['contact_email'] ?? 'hello@poematours.com'),
            ];
        }

        $content = Page::query()
            ->where('slug', $pageSlug)
            ->first()?->sections()
            ->where('key', $sectionKey)
            ->where('is_active', true)
            ->first()?->content ?? [];

        $content = is_array($content) ? $content : [];

        return [
            'breadcrumb_home_label' => $this->translatedValue($content['breadcrumb_home_label_translations'] ?? [], $locale, 'Home'),
            'breadcrumb_current_label' => $this->translatedValue($content['breadcrumb_current_label_translations'] ?? [], $locale, $defaultTitle),
            'title' => $this->translatedValue($content['title_translations'] ?? [], $locale, $defaultTitle),
            'body' => $this->translatedValue($content['body_translations'] ?? [], $locale, ''),
            'contact_email' => (string) ($content['contact_email'] ?? 'hello@poematours.com'),
        ];
    }

    private function translatedValue(mixed $translations, string $locale, string $fallback): string
    {
        if (! is_array($translations)) {
            return $fallback;
        }

        if (isset($translations[$locale]) && trim((string) $translations[$locale]) !== '') {
            return (string) $translations[$locale];
        }

        $first = collect($translations)
            ->map(fn ($value) => trim((string) $value))
            ->first(fn (string $value): bool => $value !== '');

        return $first ?: $fallback;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function homePageContent(): array
    {
        $sections = Page::query()
            ->where('slug', 'home')
            ->first()?->sections()
            ->where('is_active', true)
            ->get()
            ->keyBy('key');

            $hero = $sections?->get('home_hero')?->content ?? [];
        $spirit = $sections?->get('home_spirit')?->content ?? [];
        $tours = $sections?->get('home_tours_across_egypt')?->content ?? [];
        $lastMinute = $sections?->get('home_last_minute_packages')?->content ?? [];
        $stories = $sections?->get('home_stories')?->content ?? [];
        $whyPoema = $sections?->get('home_why_poema')?->content ?? [];

        return [
            'homeHero' => array_merge([
                'eyebrow' => 'Experience Delight.',
                'title' => 'We\'ll take care of everything.',
                'title_before' => 'We\'ll take care of',
                'title_highlight' => 'everything.',
                'title_after' => '',
                'cta_text' => 'Design Your Tour',
                'cta_url' => '/packages',
                'background_image' => 'assets/images/placeholders/banner.jpeg',
                'background_image_alt' => 'Golden sunrise over Egypt landscapes',
                'trust_items' => [],
            ], is_array($hero) ? $hero : []),
            'homeSpirit' => array_merge([
                'eyebrow' => 'Our Signature Approach',
                'title' => 'Find your dream tour to Egypt here',
                'body' => '',
            ], is_array($spirit) ? $spirit : []),
            'homeToursAcrossEgypt' => array_merge([
                'eyebrow' => 'Destinations & Themes',
                'title' => 'Tours Across Egypt',
                'items' => [],
            ], is_array($tours) ? $tours : []),
            'homeLastMinute' => is_array($lastMinute) ? $lastMinute : [],
            'homeStories' => array_merge([
                'eyebrow' => 'Enhance Your Journey',
                'title' => 'Add these experiences to your trip',
                'items' => [],
            ], is_array($stories) ? $stories : []),
            'homeWhyPoema' => array_merge([
                'eyebrow' => 'Our promise',
                'title' => 'Why Poema',
                'description' => 'The four things we get right, every single time.',
                'items' => [],
            ], is_array($whyPoema) ? $whyPoema : []),
            'homeGoogleReviews' => $this->homeGoogleReviewsContent(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function homeGoogleReviewsContent(): array
    {
        $googleReviews = Page::query()
            ->where('slug', 'home')
            ->first()?->sections()
            ->where('key', 'home_google_reviews')
            ->where('is_active', true)
            ->first()?->content ?? [];

        return array_merge([
            'title' => 'Hear what our travelers have to say',
            'image' => 'assets/images/placeholders/google-reviews.webp',
            'image_alt' => 'Travelers exploring Egypt with Poema Tours',
            'rating' => '5',
            'review_count' => '10+',
            'reviews_url' => 'https://maps.app.goo.gl/jornaw2soKN8Kkyh7?g_st=ic',
            'cta_text' => 'Will you be our next happy traveler?',
            'cta_button' => 'View on Google',
            'reviews' => [
                [
                    'reviewer_name' => 'Phegiel Talip',
                    'reviewer_role' => 'Family trip to Egypt',
                    'comment' => 'We recently went on a trip to Egypt which was arranged by Poema Tours. I really felt welcomed and accommodated. The trip exceeded my expectations. There was also a lot of personal touch and insider tips. They were very transparent with us and we were really spared from a lot of tourist traps which is considered common practice from the other companies.',
                    'rate' => 5,
                ],
                [
                    'reviewer_name' => 'A N',
                    'reviewer_role' => 'Family trip to Egypt',
                    'comment' => 'Visiting Egypt free from worry and hassle,  from landing to send off , Poema Tours will take care everything for you. This is one of the few company in the tourism industry that really promote local tourism by giving the best rates and bringing you in the best place to have your best memory. Its worth it!  Much more they give you the best people that can help you all throughout your journey in the unknown.',
                    'rate' => 5,
                ],
                [
                    'reviewer_name' => 'Rosario Rojas',
                    'reviewer_role' => 'Egypt behind the Nile',
                    'comment' => 'Poema Tours was the perfect company to choose for my Cairo/Giza trip. Sarah and Roman were so attentive to the needs of our small group. I am most grateful for their flexibility in visiting the specific sites we choose to see. Due to a situation beyond our control, our trip was cut short three days but they were able to switch plans around so that we could explore the most places possible throughout our journey. Sarah’s knowledge of history and local culture made it easy to understand the historical impact of the museums and mosques we visited. Seeing the sunset from an faluka and the pyramids at night were simply breathtaking.',
                    'rate' => 5,
                ],
            ],
        ], is_array($googleReviews) ? $googleReviews : []);
    }

    /**
     * Random activities for the home “Tours Across Egypt” grid (up to $limit).
     *
     * @return Collection<int, Activity>
     */
    private function randomActivitiesForHome(int $limit = 9): Collection
    {
        return Activity::query()
            ->with(['destination:id,name'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
