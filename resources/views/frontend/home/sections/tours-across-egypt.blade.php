<section class="section home-tours">
    <div class="container">
        <header class="home-section-head home-section-head--editorial">
            <p class="home-section-head__eyebrow">{{ $homeToursAcrossEgypt['eyebrow'] ?? 'Destinations & Themes' }}</p>
            <h2>{{ $homeToursAcrossEgypt['title'] ?? 'Tours Across Egypt' }}</h2>
        </header>

        <div class="home-tours__grid">
            @forelse (($toursAcrossEgyptActivities ?? collect()) as $activity)
                <a
                    href="{{ route('packages.index', ['activity' => $activity->slug]) }}"
                    class="home-tours__card"
                    aria-label="{{ $activity->name }}"
                >
                    <img
                        src="{{ $activity->imagePublicUrl() ?? asset('assets/images/placeholders/banner.jpeg') }}"
                        alt=""
                        loading="lazy"
                        decoding="async"
                    >
                    <span class="home-tours__card-label">{{ $activity->name }}</span>
                    <span class="home-tours__card-cta" aria-hidden="true">{{ __('Explore') }}</span>
                </a>
            @empty
                <p class="home-tours__empty">
                    {{ __('No activities are available at the moment.') }}
                </p>
            @endforelse
        </div>
    </div>
</section>
