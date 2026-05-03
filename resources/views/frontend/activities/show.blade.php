@extends('frontend.layouts.app')

@section('content')
    <section class="activity-hero packages-hero--split">
        <div class="packages-hero__media">
            <img src="{{ asset($heroImage) }}" alt="{{ $activityName }} activity hero">
            <div class="packages-hero__overlay"></div>
            <div class="packages-hero__media-content">
                <h1>{{ $activityName }} {{ $activityLabels['hero_title_suffix'] ?? 'Activities' }}</h1>
                <a href="{{ route('our.journeys') }}" class="packages-hero__view-link">
                    {{ $activityLabels['hero_view_journeys_label'] ?? 'View All Journeys' }}
                </a>
            </div>
        </div>
        <div class="packages-hero__content">
            <div class="container">
                <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">{{ $activityLabels['breadcrumb_home_label'] ?? 'Home' }}</a>
                    <span>/</span>
                    <a href="{{ route('packages.index') }}">{{ $activityLabels['breadcrumb_packages_label'] ?? 'Packages' }}</a>
                    <span>/</span>
                    <span>{{ $activityName }}</span>
                </nav>
                <h2>{{ $activityLabels['hero_side_title'] ?? '' }}</h2>
                <p>
                    {{ $activityLabels['hero_side_description'] ?? '' }}
                </p>
            </div>
        </div>
    </section>


    <section class="section activity-intro home-reveal" data-home-reveal data-home-reveal-delay="70">
        <div class="container">
            <h2 class="section-title">{{ $activityLabels['intro_title'] ?? ($activityName . ' in Egypt') }}</h2>
            <p class="section-subtitle">{{ $activityDescription }}</p>
        </div>
    </section>

    <section class="activity-seasons home-reveal" data-home-reveal data-home-reveal-delay="90">
        <div class="container">
            <h2 class="section-title home-reveal" data-home-reveal data-home-reveal-delay="110">
                {{ $activityLabels['activities_list_title'] ?? 'All Activities' }}
            </h2>
            <div class="activity-seasons__row">
                @foreach (($activityCards ?? []) as $item)
                    <article
                        class="activity-season-card home-reveal"
                        data-home-reveal
                        data-home-reveal-delay="{{ 120 + ($loop->index * 75) }}"
                    >
                        <a href="{{ $item['link'] ?? '#' }}" class="activity-season-card__link" aria-label="{{ $item['name'] ?? 'Activity card' }}">
                            <img src="{{ asset($item['image'] ?? 'assets/images/placeholders/banner.jpeg') }}" alt="{{ $item['name'] ?? 'Activity card' }} in Egypt">
                            <div class="activity-season-card__overlay"></div>
                            <span>{{ $item['name'] ?? '' }}</span>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section activity-trips home-reveal" data-home-reveal data-home-reveal-delay="120">
        <div class="container">
            <h2 class="section-title home-reveal" data-home-reveal data-home-reveal-delay="130">
                {{ $activityLabels['trips_title'] ?? ($activityName . ' Trips') }}
            </h2>
            <div class="packages-grid-activity">
                @forelse ($activityTrips as $package)
                    <div class="activity-trips__item home-reveal" data-home-reveal data-home-reveal-delay="{{ 180 + ($loop->index * 80) }}">
                        @include('frontend.packages.cards.grid-card', ['package' => $package])
                    </div>
                @empty
                    <p class="activity-trips__empty home-reveal" data-home-reveal data-home-reveal-delay="170">
                        {{ $activityLabels['empty_trips_label'] ?? 'No trips are available for this activity yet.' }}
                    </p>
                @endforelse
            </div>
        </div>
    </section>

    @include('frontend.pages.aboutus.sections.reels')
@endsection
