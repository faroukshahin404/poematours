<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        /** @var array{title:string,meta_description:?string,meta_keywords:string,canonical_url:?string,og_title:string,og_description:?string,og_type:string,og_url:?string,og_image:?string} $pageSeo */
        $pageSeo = $pageSeo ?? [];
        $seoTitle = $pageSeo['title'] ?? 'Poema Tours | Enter Egypt';
        $seoDescription = $pageSeo['meta_description'] ?? null;
        $seoKeywords = trim((string) ($pageSeo['meta_keywords'] ?? ''));
        $canonicalUrl = $pageSeo['canonical_url'] ?? null;
        $ogTitle = $pageSeo['og_title'] ?? $seoTitle;
        $ogDescription = $pageSeo['og_description'] ?? null;
        $ogType = $pageSeo['og_type'] ?? 'website';
        $ogUrl = $pageSeo['og_url'] ?? url()->current();
        $ogImage = $pageSeo['og_image'] ?? null;
    @endphp
    <title>{{ $seoTitle }}</title>
    @if (! empty($seoDescription))
        <meta name="description" content="{{ $seoDescription }}">
    @endif
    @if ($seoKeywords !== '')
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endif
    @if (! empty($canonicalUrl))
        <link rel="canonical" href="{{ $canonicalUrl }}">
    @endif
    <meta property="og:title" content="{{ $ogTitle }}">
    @if (! empty($ogDescription))
        <meta property="og:description" content="{{ $ogDescription }}">
    @endif
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:url" content="{{ $ogUrl }}">
    @if (! empty($ogImage))
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta property="og:site_name" content="Poema Tours">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    @if (! empty($ogDescription))
        <meta name="twitter:description" content="{{ $ogDescription }}">
    @endif
    @if (! empty($ogImage))
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/brand/favicon.svg') }}">
    @php
        $fontMcQueenRegular = public_path('assets/fonts/McQueen/McQueen Grotesk/Fonts WEB/McQueenGroteskTrial-Regular.woff2');
        $fontArgentRegular = public_path('assets/fonts/Argent-CF/Demo_Fonts/Fontspring-DEMO-argentcf-regular.otf');
        $fontMcQueenPreloadUrl = asset(
            'assets/fonts/McQueen/' . rawurlencode('McQueen Grotesk') . '/' . rawurlencode('Fonts WEB') . '/McQueenGroteskTrial-Regular.woff2'
        );
    @endphp
    @if (file_exists($fontMcQueenRegular))
        <link rel="preload" href="{{ $fontMcQueenPreloadUrl }}" as="font" type="font/woff2" crossorigin>
    @endif
    @if (file_exists($fontArgentRegular))
        <link rel="preload" href="{{ asset('assets/fonts/Argent-CF/Demo_Fonts/Fontspring-DEMO-argentcf-regular.otf') }}" as="font" type="font/otf" crossorigin>
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/theme-variables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/frontend.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forms.css') }}">
    <style>
        html {
            font-size: 18px;
        }
    </style>
    @stack('styles')
</head>
@php
    $headerDestinations = $headerDestinations ?? collect();
    $isHomepage = request()->routeIs('home');
    $bodyClass = $isHomepage ? 'is-homepage' : 'is-inner-page';

    // First section already accounts for the fixed header (hero, legal shell, auth, forms, etc.)
    $routesWithSelfHeaderOffset = [
        'home',
        'packages.index',
        'packages.show',
        'packages.gallery',
        'packages.reviews',
        'packages.book',
        'about.us',
        'activities.show',
        'search',
        'our.journeys',
        'our.journeys.show',
        'terms.of.use',
        'privacy.policy',
        'pages.show',
      
        
        'password.request',
        'customize.create',
        'reservation.create',
        'payment.success',
        'payment.failure',
    ];
    $siteMainClass = request()->routeIs($routesWithSelfHeaderOffset)
        ? 'site-main site-main--flush'
        : 'site-main site-main--offset';
@endphp
<body class="{{ $bodyClass }}">
    @include('frontend.layouts.header')
    <button
        type="button"
        class="mobile-drawer__backdrop"
        aria-hidden="true"
        tabindex="-1"
        style="display: none;"
        data-mobile-menu-backdrop
    ></button>

    <div class="mobile-drawer" id="mobileNavigation" aria-hidden="true" data-mobile-menu>
        <div class="mobile-drawer__header">
            <button type="button" class="mobile-drawer__close" data-mobile-menu-close aria-label="Close menu">
                <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <nav class="mobile-drawer__links" aria-label="Mobile navigation">
            <a href="{{ route('about.us') }}">About Us</a>
            <button
                type="button"
                aria-expanded="false"
                aria-controls="mobileDestinationPanel"
                data-mobile-destination-toggle
            >
                Destinations in Egypt
            </button>
            <div
                class="mobile-drawer__destination-panel"
                id="mobileDestinationPanel"
                data-mobile-destination-list
                aria-hidden="true"
            >
                <div class="mobile-drawer__destination-rail" role="list" aria-label="Egypt destinations">
                    @foreach ($headerDestinations as $destination)
                        <a
                            href="{{ route('packages.index', ['destination' => $destination->slug]) }}"
                            class="mobile-drawer__destination-card"
                            role="listitem"
                        >
                            <img
                                src="{{ $destination->imagePublicUrl() ?? asset('assets/images/placeholders/banner.jpeg') }}"
                                alt="{{ $destination->name }}"
                                loading="lazy"
                                decoding="async"
                            >
                            <span class="mobile-drawer__destination-label">{{ $destination->name }}</span>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('destinations.index') }}" class="mobile-drawer__all-destinations-btn">
                    All Destinations
                </a>
            </div>
            <a href="{{ route('activities.show', 'culture') }}">Activities</a>
            <a href="{{ route('our.journeys') }}">Our Journeys</a>
            <a href="{{ route('customize.create', request()->filled('package_id') ? ['package_id' => request('package_id')] : []) }}">Customize Tour</a>
            <a href="{{ route('reservation.create') }}">Reservation</a>
           
            
        </nav>
    </div>

    <main class="{{ $siteMainClass }}">
        @if (session('status'))
            <div class="container" style="margin-top: 1rem;">
                <div style="padding: 0.75rem 1rem; border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534; border-radius: 0.5rem;">
                    {{ session('status') }}
                </div>
            </div>
        @endif
        @yield('content')
    </main>
    @include('frontend.layouts.footer')

    @unless (request()->routeIs('packages.show'))
        <a
            href="https://wa.me/201277339611?text={{ rawurlencode('Hello Poema Tours, I would like to inquire about a trip.') }}"
            class="whatsapp-float"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="{{ __('Chat with us on WhatsApp') }}"
        >
            <svg class="whatsapp-float__icon" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
            </svg>
            <span class="whatsapp-float__label">{{ __('WhatsApp') }}</span>
        </a>
    @endunless

    <script src="{{ asset('assets/js/frontend.js') }}"></script>
    @stack('scripts')
</body>
</html>
