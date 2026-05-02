@extends('frontend.layouts.app')

@section('content')
    <section class="activity-hero packages-hero--split">
        <div class="packages-hero__media">
            <img src="{{ asset($heroImage) }}" alt="{{ $activityName }} activity hero">
            <div class="packages-hero__overlay"></div>
            <div class="packages-hero__media-content">
                <h1>{{ $activityName }} Activities</h1>
                <a href="{{ route('our.journeys') }}" class="packages-hero__view-link">View All Journeys</a>
            </div>
        </div>
        <div class="packages-hero__content">
            <div class="container">
                <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('packages.index') }}">Packages</a>
                    <span>/</span>
                    <span>{{ $activityName }}</span>
                </nav>
                <h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit sed do eiusmod tempor incididunt.</h2>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
               
                </p>
            </div>
        </div>
    </section>


    <section class="section activity-intro home-reveal" data-home-reveal data-home-reveal-delay="70">
        <div class="container">
            <h2 class="section-title">{{ $activityName }} in Egypt</h2>
            <p class="section-subtitle">{{ $activityDescription }}</p>
        </div>
    </section>

    <section class="activity-seasons home-reveal" data-home-reveal data-home-reveal-delay="90">
        <div class="container">
            <div class="activity-seasons__row">
                @foreach ($seasonCards as $season)
                    <article
                        class="activity-season-card home-reveal"
                        data-home-reveal
                        data-home-reveal-delay="{{ 120 + ($loop->index * 75) }}"
                    >
                        <img src="{{ asset($season['image']) }}" alt="{{ $season['name'] }} in Egypt">
                        <div class="activity-season-card__overlay"></div>
                        <span>{{ $season['name'] }}</span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section activity-trips home-reveal" data-home-reveal data-home-reveal-delay="120">
        <div class="container">
            <h2 class="section-title home-reveal" data-home-reveal data-home-reveal-delay="130">{{ $activityName }} Trips</h2>
            <div class="packages-grid-activity">
                @forelse ($activityTrips as $package)
                    <div class="activity-trips__item home-reveal" data-home-reveal data-home-reveal-delay="{{ 180 + ($loop->index * 80) }}">
                        @include('frontend.packages.cards.grid-card', ['package' => $package])
                    </div>
                @empty
                    <p class="activity-trips__empty home-reveal" data-home-reveal data-home-reveal-delay="170">No trips are available for this activity yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    @include('frontend.pages.aboutus.sections.reels')
@endsection
