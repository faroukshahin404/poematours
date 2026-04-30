@extends('frontend.layouts.app')

@section('content')
    <section class="packages-hero packages-hero--split">
        <div class="packages-hero__media">
            <img src="{{ asset($galleryImages[0] ?? 'assets/images/placeholders/banner.jpeg') }}" alt="Travel destinations in Egypt">
            <div class="packages-hero__overlay"></div>
            <div class="packages-hero__media-content">
                <h1>Luxury Vacations in Egypt</h1>
                <a href="{{ route('our.journeys') }}" class="packages-hero__view-link">View All Journeys</a>
            </div>
        </div>
        <div class="packages-hero__content">
            <div class="container">
                <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <span>Lorem Destination</span>
                    <span>/</span>
                    <span>Lorem Egypt</span>
                </nav>
                <h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor.</h2>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat.
                </p>
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
                <div class="recommended-journeys__switch" role="tablist" aria-label="Journey style">
                    <button type="button" role="tab" aria-selected="false">Travel Privately</button>
                    <button type="button" role="tab" aria-selected="true" class="is-active">Join a Small Group</button>
                </div>
            </div>

            <div class="packages-grid-recommended  packages-grid--recommended">
                @forelse($packages as $package)
                    @break($loop->iteration > 3)
                    @include('frontend.packages.cards.list-card', ['package' => $package])
                @empty
                    <p class="packages-empty">No recommended journeys available right now.</p>
                @endforelse
            </div>
        </div>
    </section>

    @php
        $experienceCards = [
            [
                'location' => 'Giza, Egypt',
                'title' => 'Lorem ipsum dolor sit amet consectetur',
                'image' => $galleryImages[1] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'location' => 'Aswan, Egypt',
                'title' => 'Sed do eiusmod tempor incididunt ut labore',
                'image' => $galleryImages[2] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'location' => 'Luxor, Egypt',
                'title' => 'Ut enim ad minim veniam quis nostrud',
                'image' => $galleryImages[3] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'location' => 'Cairo, Egypt',
                'title' => 'Duis aute irure dolor in reprehenderit',
                'image' => $galleryImages[4] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'location' => 'Nile Valley, Egypt',
                'title' => 'Excepteur sint occaecat cupidatat non proident',
                'image' => $galleryImages[5] ?? 'assets/images/placeholders/banner.jpeg',
            ],
        ];
    @endphp

    <section class="experiences-section section" id="destinations">
        <div class="container">
            <div class="experiences-section__head">
                <p>Enhance your journey with add-on experiences from our local experts</p>
                <h2>Discover a selection of incredible add-on experiences curated for unforgettable moments.</h2>
            </div>
            <div class="experiences-section__grid">
                @foreach($experienceCards as $experience)
                    @include('frontend.packages.cards.experience-destination-card', ['experience' => $experience])
                @endforeach
            </div>
        </div>
    </section>

    @php
        $accommodations = [
            [
                'location' => 'Nile River, Egypt',
                'title' => 'Nile Adventurer, an A&K Sanctuary',
                'image' => $galleryImages[1] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'location' => 'Nile River, Egypt',
                'title' => 'Zein Nile Chateau, an A&K Sanctuary',
                'image' => $galleryImages[2] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'location' => 'Nile River, Egypt',
                'title' => 'Sun Boat IV, an A&K Sanctuary',
                'image' => $galleryImages[3] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'location' => 'Cairo, Egypt',
                'title' => 'The St. Regis Cairo',
                'image' => $galleryImages[4] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'location' => 'Aswan, Egypt',
                'title' => 'Old Cataract Legend Collection',
                'image' => $galleryImages[5] ?? 'assets/images/placeholders/banner.jpeg',
            ],
        ];
    @endphp

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
                    @foreach($accommodations as $accommodation)
                        @include('frontend.packages.cards.accommodation-card', ['accommodation' => $accommodation])
                    @endforeach
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

    @php
        $placesToVisit = [
            [
                'title' => 'The Nile',
                'description' => 'The most captivating river journey, where timeless landscapes meet unforgettable moments.',
                'image' => $galleryImages[1] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'title' => 'Cairo & the Pyramids of Giza',
                'description' => 'Awe-inspiring archaeology and world-famous monuments that tell stories of ancient Egypt.',
                'image' => $galleryImages[2] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'title' => 'Luxor',
                'description' => 'A historic open-air museum of temples, tombs, and extraordinary pharaonic heritage.',
                'image' => $galleryImages[3] ?? 'assets/images/placeholders/banner.jpeg',
            ],
        ];
    @endphp

    <section class="places-visit-section section" id="places-to-visit">
        <div class="container">
            <div class="places-visit-section__head">
                <h2>Places We Love in Egypt</h2>
            </div>
            <div class="places-visit-section__grid">
                @foreach($placesToVisit as $place)
                    @include('frontend.packages.cards.place-to-visit-card', ['place' => $place])
                @endforeach
            </div>
        </div>
    </section>

    @php
        $waysToExplore = [
            [
                'key' => 'tailormade',
                'label' => 'Tailormade Journeys',
                'eyebrow' => 'Tailormade Journeys',
                'title' => 'Adventures custom made for you.',
                'description' => 'Every private journey is unique. Whether you want to personalize one of our expert-designed journeys, or choose a bespoke route, we craft every detail around your travel style and pace.',
                'cta' => 'Explore Tailormade Journeys',
                'image' => $galleryImages[2] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'key' => 'small-group',
                'label' => 'Small Group Journeys',
                'eyebrow' => 'Small Group Journeys',
                'title' => 'Travel deeper with expert-led small groups.',
                'description' => 'Join like-minded travelers on curated departures with expert Egyptologists and seamless logistics. Small groups mean privileged access, richer storytelling, and more meaningful shared moments.',
                'cta' => 'Explore Small Group Journeys',
                'image' => $galleryImages[3] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'key' => 'small-jet',
                'label' => 'Small Jet Journeys',
                'eyebrow' => 'Small Jet Journeys',
                'title' => 'See more in style, with less travel time.',
                'description' => 'Designed for travelers who value comfort and efficiency, these itineraries blend iconic highlights with rare access. Enjoy premium pacing and effortless transfers between extraordinary destinations.',
                'cta' => 'Explore Small Jet Journeys',
                'image' => $galleryImages[4] ?? 'assets/images/placeholders/banner.jpeg',
            ],
            [
                'key' => 'river-cruises',
                'label' => 'River Cruises',
                'eyebrow' => 'River Cruises',
                'title' => 'Sail timeless routes along the Nile.',
                'description' => 'Discover Egypt from the river with intimate vessels, refined service, and immersive shore experiences. Cruise between ancient temples and storied towns while enjoying elegant onboard comfort.',
                'cta' => 'Explore River Cruises',
                'image' => $galleryImages[5] ?? 'assets/images/placeholders/banner.jpeg',
            ],
        ];
        $defaultWay = $waysToExplore[0];
    @endphp

    <section class="ways-explore-section section" id="ways-to-explore" data-ways-explore>
        <div class="container">
            <div class="ways-explore-section__head">
                <p>Ways to Explore Egypt</p>
                <h2>What type of adventure are you looking for?</h2>
            </div>

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
                    <a href="#" data-ways-feature-cta>{{ $defaultWay['cta'] }}</a>
                </div>
            </div>
        </div>
    </section>

    @php
        $mapStops = [
            ['name' => 'Alexandria', 'x' => 52, 'y' => 16, 'count' => 2],
            ['name' => 'Cairo', 'x' => 55, 'y' => 35, 'count' => 3],
            ['name' => 'Luxor', 'x' => 57, 'y' => 51, 'count' => 5],
            ['name' => 'Aswan', 'x' => 55, 'y' => 67, 'count' => 2],
            ['name' => 'Abu Simbel', 'x' => 53, 'y' => 79, 'count' => 1],
        ];
    @endphp

    <section class="map-section section" id="map-section">
        <div class="container">
            <div class="map-section__canvas" role="img" aria-label="Egypt map with journey points">
                <div class="map-section__controls" aria-hidden="true">
                    <button type="button">+</button>
                    <button type="button">-</button>
                </div>
                @foreach($mapStops as $stop)
                    <span class="map-section__pin" style="left: {{ $stop['x'] }}%; top: {{ $stop['y'] }}%;">
                        {{ $stop['count'] }}
                    </span>
                    <span class="map-section__label" style="left: calc({{ $stop['x'] }}% + 10px); top: calc({{ $stop['y'] }}% - 12px);">
                        {{ $stop['name'] }}
                    </span>
                @endforeach
            </div>
        </div>
    </section>

    <div class="accommodation-modal" data-accommodation-modal aria-hidden="true">
        <div class="accommodation-modal__backdrop" data-accommodation-close></div>
        <div class="accommodation-modal__dialog" role="dialog" aria-modal="true" aria-label="Accommodation details">
            <button type="button" class="accommodation-modal__close" data-accommodation-close aria-label="Close modal">
                <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>

            <div class="accommodation-modal__header">
                <h3 data-accommodation-modal-title>Accommodation Title</h3>
            </div>

            <div class="accommodation-modal__tabs" role="tablist" aria-label="Accommodation details tabs">
                <button type="button" class="is-active" role="tab" data-accommodation-tab="boat" aria-selected="true">The Boat</button>
                <button type="button" role="tab" data-accommodation-tab="cabins" aria-selected="false">The Cabins & Suites</button>
                <button type="button" role="tab" data-accommodation-tab="food" aria-selected="false">Food & Drink</button>
            </div>

            <div class="accommodation-modal__panel is-active" data-accommodation-panel="boat">
                <h4 data-accommodation-panel-title="boat">The finest Egyptian materials meet modern comfort</h4>
                <p data-accommodation-panel-description="boat">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.
                </p>
            </div>

            <div class="accommodation-modal__panel" data-accommodation-panel="cabins">
                <h4 data-accommodation-panel-title="cabins">Cabins and suites crafted for serene comfort</h4>
                <p data-accommodation-panel-description="cabins">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Excepteur sint occaecat cupidatat non proident.
                </p>
            </div>

            <div class="accommodation-modal__panel" data-accommodation-panel="food">
                <h4 data-accommodation-panel-title="food">Locally inspired cuisine with modern presentation</h4>
                <p data-accommodation-panel-description="food">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.
                </p>
            </div>

            <div class="accommodation-modal__hero-image">
                <img src="{{ asset('assets/images/placeholders/banner.jpeg') }}" alt="Accommodation preview" data-accommodation-modal-image>
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
