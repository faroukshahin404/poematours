@extends('frontend.layouts.app')

@section('content')
    <section class="activity-hero">
        <img src="{{ asset($heroImage) }}" alt="{{ $activityName }} activity hero">
        <div class="activity-hero__overlay"></div>
        <div class="container activity-hero__content">
            <nav class="packages-breadcrumb home-reveal" aria-label="Breadcrumb" data-home-reveal data-home-reveal-delay="40">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('packages.index') }}">Packages</a>
                <span>/</span>
                <span>{{ $activityName }}</span>
            </nav>
            <h1 class="home-reveal" data-home-reveal data-home-reveal-delay="130">{{ $activityName }} Activities</h1>
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
