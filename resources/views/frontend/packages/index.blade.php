@extends('frontend.layouts.app')

@section('content')
    <section class="packages-hero">
        <img src="{{ asset($galleryImages[0] ?? 'assets/images/placeholders/banner.jpeg') }}" alt="Travel destinations in Egypt">
        <div class="packages-hero__overlay"></div>
        <div class="container packages-hero__content">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Travel Destinations</span>
                <span>/</span>
                <span>
                    {{ $filters['destination'] ? ucfirst($filters['destination']) : 'All Destinations' }}
                    @if(!empty($filters['travel_date']))
                        - {{ $filters['travel_date'] }}
                    @endif
                    ({{ $filters['adults'] }} Adults, {{ $filters['kids'] }} Kids)
                </span>
            </nav>
            <h1>Curated Egypt Travel Packages</h1>
            <p>Discover refined experiences shaped around your selected travel preferences and dates.</p>
        </div>
    </section>

    <section class="packages-gallery section">
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
    </section>

    <section class="packages-results section">
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
