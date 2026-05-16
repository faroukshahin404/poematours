@extends('frontend.layouts.app')

@section('content')
    @php
        $heroTitle = $selectedDestination?->name
            ? "Luxury Vacations in {$selectedDestination->name}"
            : 'Luxury Vacations in Egypt';
        $heroBreadcrumbDestination = $selectedDestination?->name ?? 'Destinations';
        $heroDescription = $selectedDestination?->name
            ? "Discover curated journeys, handpicked stays, and immersive experiences in {$selectedDestination->name}."
            : 'Discover curated journeys, handpicked stays, and immersive experiences across Egypt.';
    @endphp

    <section class="packages-hero packages-hero--split">
        <div class="packages-hero__media">
            <img src="{{ asset($galleryImages[0] ?? 'assets/images/placeholders/banner.jpeg') }}" alt="Travel destinations in Egypt">
            <div class="packages-hero__overlay"></div>
            <div class="packages-hero__media-content">
                <h1>{{ $heroTitle }}</h1>
                <a href="{{ route('our.journeys') }}" class="packages-hero__view-link">View All Journeys</a>
            </div>
        </div>
        <div class="packages-hero__content">
            <div class="container">
                <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <span>{{ $heroBreadcrumbDestination }}</span>
                    <span>/</span>
                    <span>Egypt</span>
                </nav>
                <h2>{{ $heroTitle }}</h2>
                <p>{{ $heroDescription }}</p>
            </div>
        </div>
    </section>

    <nav class="inner-page-nav" data-sticky-subnav>
        <div class="container inner-page-nav__inner">
            <a href="#recommended-journeys" class="is-active">Recommended Journeys</a>
            <a href="#destinations">Destinations</a>
            <a href="#accommodations">Accommodations</a>
            <a href="#places-to-visit">Places To Visit</a>
            <a href="#ways-to-explore">Ways To Explore</a>
            <a href="#map-section">Map</a>
            <a href="#packages">All Journeys</a>
        </div>
    </nav>

    <section class="recommended-journeys section" id="recommended-journeys">
        <div class="container">
            <div class="recommended-journeys__head">
                <p>Recommended Journeys</p>
                <h2>Our Favorite Adventures in Egypt Right Now</h2>
                @php($tripTypeFilter = $filters['trip_type'] ?? '')
                <div class="recommended-journeys__switch" role="tablist" aria-label="Journey style">
                    <button
                        type="button"
                        role="tab"
                        data-recommended-trip-type="private"
                        aria-selected="{{ $tripTypeFilter === 'private' ? 'true' : 'false' }}"
                        class="{{ $tripTypeFilter === 'private' ? 'is-active' : '' }}"
                    >Travel Privately</button>
                    <button
                        type="button"
                        role="tab"
                        data-recommended-trip-type="small-group"
                        aria-selected="{{ $tripTypeFilter === 'small-group' ? 'true' : 'false' }}"
                        class="{{ $tripTypeFilter === 'small-group' ? 'is-active' : '' }}"
                    >Join a Small Group</button>
                </div>
            </div>

            <div class="packages-grid-recommended  packages-grid--recommended">
                @forelse($recommendedPackages as $package)
                    @break($loop->iteration > 3)
                    @include('frontend.packages.cards.list-card', ['package' => $package])
                @empty
                    <p class="packages-empty">No recommended journeys available right now.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="experiences-section section" id="destinations">
        <div class="container">
            <div class="experiences-section__head">
                <p>Destinations</p>
                <h2>Explore Egypt destinations available for your next journey.</h2>
            </div>
            <div class="experiences-section__grid">
                @foreach(($destinations ?? []) as $experience)
                    @include('frontend.packages.cards.experience-destination-card', ['experience' => $experience])
                @endforeach
            </div>
        </div>
    </section>

    <section class="accommodations-section section" id="accommodations">
        <div class="container">
            <div class="accommodations-section__head">
                <h2>Step into Egypt's most enchanting stays. Every accommodation is handpicked to bring you closer to beauty.</h2>
            </div>
            <div class="accommodations-carousel">
                <button
                    type="button"
                    class="accommodations-carousel__nav accommodations-carousel__nav--prev"
                    data-accommodation-prev
                    aria-label="Previous accommodations"
                >
                    <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <div class="accommodations-carousel__track" data-accommodation-track>
                    @forelse(($accommodations ?? []) as $accommodation)
                        @include('frontend.packages.cards.accommodation-card', ['accommodation' => $accommodation])
                    @empty
                        <p class="packages-empty">No accommodations available for the selected destination.</p>
                    @endforelse
                </div>
                <button
                    type="button"
                    class="accommodations-carousel__nav accommodations-carousel__nav--next"
                    data-accommodation-next
                    aria-label="Next accommodations"
                >
                    <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
        </div>
    </section>

    <section class="places-visit-section section" id="places-to-visit">
        <div class="container">
            <div class="places-visit-section__head">
                <h2>Places We Love in Egypt</h2>
            </div>
            <div class="places-visit-section__grid">
                @forelse(($placesToVisit ?? []) as $place)
                    @include('frontend.packages.cards.place-to-visit-card', ['place' => $place])
                @empty
                    <p class="packages-empty">No places to visit available for the selected destination.</p>
                @endforelse
            </div>
        </div>
    </section>

        @php($defaultWay = ($waysToExplore ?? [])[0] ?? null)

    <section class="ways-explore-section section" id="ways-to-explore" data-ways-explore>
        <div class="container">
            <div class="ways-explore-section__head">
                <p>Ways to Explore Egypt</p>
                <h2>What type of adventure are you looking for?</h2>
            </div>

            @if($defaultWay)
                <div class="ways-explore-tabs" role="tablist" aria-label="Ways to explore">
                    @foreach($waysToExplore as $way)
                        <button
                            type="button"
                            role="tab"
                            class="{{ $loop->first ? 'is-active' : '' }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            data-ways-tab
                            data-way-eyebrow="{{ $way['eyebrow'] }}"
                            data-way-title="{{ $way['title'] }}"
                            data-way-description="{{ $way['description'] }}"
                            data-way-cta="{{ $way['cta'] }}"
                            data-way-url="{{ $way['url'] }}"
                            data-way-image="{{ asset($way['image']) }}"
                        >
                            {{ $way['label'] }}
                        </button>
                    @endforeach
                </div>

                <div class="ways-explore-feature">
                    <div class="ways-explore-feature__media">
                        <img src="{{ asset($defaultWay['image']) }}" alt="{{ $defaultWay['title'] }}" data-ways-feature-image>
                    </div>
                    <div class="ways-explore-feature__content">
                        <p data-ways-feature-eyebrow>{{ $defaultWay['eyebrow'] }}</p>
                        <h3 data-ways-feature-title>{{ $defaultWay['title'] }}</h3>
                        <p data-ways-feature-description>{{ $defaultWay['description'] }}</p>
                        <a href="{{ $defaultWay['url'] }}" data-ways-feature-cta>{{ $defaultWay['cta'] }}</a>
                    </div>
                </div>
            @else
                <p class="packages-empty">No category journeys available right now.</p>
            @endif
        </div>
    </section>

    <section class="map-section section" id="map-section">
        <div class="container">
            <div
                id="packagesMap"
                class="map-section__canvas"
                role="img"
                aria-label="Egypt map with destination points"
                data-destinations-map
                data-destinations='@json($mapDestinations)'
            >
            </div>
        </div>
    </section>

    <div class="accommodation-modal" data-accommodation-modal aria-hidden="true">
        <div class="accommodation-modal__backdrop" data-accommodation-close></div>
        <div
            class="accommodation-modal__dialog"
            role="dialog"
            aria-modal="true"
            aria-labelledby="accommodation-modal-title"
        >
            <button type="button" class="accommodation-modal__close" data-accommodation-close aria-label="Close modal">
                <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>

            <header class="accommodation-modal__header">
                <h2 id="accommodation-modal-title" class="accommodation-modal__title" data-accommodation-modal-title>
                    Accommodation Title
                </h2>
            </header>

            <div class="accommodation-modal__body">
                <div class="accommodation-modal__slider" data-accommodation-slider>
                    <div class="accommodation-modal__slider-viewport">
                        <img
                            src="{{ asset('assets/images/placeholders/banner.jpeg') }}"
                            alt=""
                            class="accommodation-modal__slider-img"
                            data-accommodation-modal-image
                            width="800"
                            height="520"
                        >
                        <button
                            type="button"
                            class="accommodation-modal__slider-nav accommodation-modal__slider-nav--prev"
                            data-accommodation-slide-prev
                            aria-label="Previous image"
                        >
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <button
                            type="button"
                            class="accommodation-modal__slider-nav accommodation-modal__slider-nav--next"
                            data-accommodation-slide-next
                            aria-label="Next image"
                        >
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>

                <div class="accommodation-modal__description">
                    <div class="accommodation-modal__description-text" data-accommodation-modal-description></div>
                </div>
            </div>
        </div>
    </div>

    <div class="experience-modal" data-experience-modal aria-hidden="true">
        <div class="experience-modal__backdrop" data-experience-close></div>
        <div class="experience-modal__dialog" role="dialog" aria-modal="true" aria-label="Experience details">
            <button type="button" class="experience-modal__close" data-experience-close aria-label="Close modal">
                <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <div class="experience-modal__media">
                <img src="{{ asset('assets/images/placeholders/banner.jpeg') }}" alt="Experience image" data-experience-modal-image>
            </div>
            <div class="experience-modal__content">
                <h3 data-experience-modal-title>Experience Title</h3>
                <p data-experience-modal-description>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat.
                </p>
            </div>
        </div>
    </div>

    {{-- <section class="packages-gallery section">
        <div class="container">
            <div class="packages-gallery__header">
                <h2>Destination Gallery</h2>
                <a href="{{ route('packages.gallery') }}" class="packages-gallery__link">Open Full Gallery</a>
            </div>
            <div class="packages-gallery__grid">
                @foreach($galleryImages as $image)
                    <figure class="packages-gallery__item {{ $loop->first ? 'packages-gallery__item--main' : '' }}">
                        <img src="{{ asset($image) }}" alt="Egypt destination gallery image">
                    </figure>
                @endforeach
            </div>
        </div>
    </section>

    <section class="offers-ticker" aria-label="Current special offers">
        <div class="offers-ticker__track">
            @foreach($offers as $offer)
                <span>{{ $offer }}</span>
            @endforeach
            @foreach($offers as $offer)
                <span>{{ $offer }}</span>
            @endforeach
        </div>
    </section> --}}

    <section class="packages-results section" id="packages">
        <div class="container packages-results__layout">
            <button
                type="button"
                class="packages-filters__toggle"
                data-packages-filters-toggle
                aria-expanded="false"
                aria-controls="packagesFiltersPanel"
            >
                Show Filters
            </button>

            <div
                id="packagesFiltersPanel"
                class="packages-filters__panel"
                data-packages-filters-panel
                aria-hidden="true"
            >
                @include('frontend.packages.partials.filters')
            </div>

            <div class="packages-results__content">
                <div class="packages-results__toolbar">
                    <p>{{ $packages->count() }} package(s) found</p>
                    <div class="packages-results__view-switch">
                        <a href="{{ route('packages.index', array_merge(request()->query(), ['view' => 'list'])) }}" class="{{ $filters['view'] === 'list' ? 'is-active' : '' }}">List</a>
                        <a href="{{ route('packages.index', array_merge(request()->query(), ['view' => 'grid'])) }}" class="{{ $filters['view'] === 'grid' ? 'is-active' : '' }}">Grid</a>
                        <a href="{{ route('packages.index', array_merge(request()->query(), ['view' => 'map'])) }}" class="{{ $filters['view'] === 'map' ? 'is-active' : '' }}">Map</a>
                    </div>
                </div>

                @includeIf('frontend.packages.views.' . ($filters['view'] ?: 'grid'), ['packages' => $packages])
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >
    <style>
        .packages-map-dot-label {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid #e3ded8;
            border-radius: 4px;
            color: #6b3c2a;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.04em;
            padding: 2px 6px;
            text-transform: uppercase;
            box-shadow: none;
        }

        .packages-map-dot-label::before {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-recommended-trip-type]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const tripType = button.getAttribute('data-recommended-trip-type');
                    if (!tripType) {
                        return;
                    }
                    const url = new URL(window.location.href);
                    url.searchParams.set('trip_type', tripType);
                    window.location.assign(url.toString());
                });
            });

            const mapElement = document.querySelector('[data-destinations-map]');
            if (!mapElement || typeof L === 'undefined') {
                return;
            }

            let destinations = [];
            try {
                destinations = JSON.parse(mapElement.dataset.destinations || '[]');
            } catch (error) {
                destinations = [];
            }

            const map = L.map(mapElement, {
                zoomControl: true,
                scrollWheelZoom: true,
            }).setView([26.8206, 30.8025], 6);

            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 20,
                subdomains: 'abcd',
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            }).addTo(map);

            destinations.forEach((destination) => {
                const marker = L.circleMarker([destination.lat, destination.lng], {
                    radius: 6,
                    color: '#7f3f27',
                    weight: 1,
                    fillColor: '#a15233',
                    fillOpacity: 0.95,
                }).addTo(map);

                marker.bindTooltip(destination.name, {
                    permanent: true,
                    direction: 'top',
                    offset: [0, -8],
                    className: 'packages-map-dot-label',
                });

                marker.on('click', function () {
                    if (destination.url) {
                        window.location.href = destination.url;
                    }
                });
            });
        });
    </script>
@endpush
