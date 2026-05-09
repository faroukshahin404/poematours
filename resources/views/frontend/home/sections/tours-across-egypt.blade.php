<section class="section">
    <div class="container">
        <div class="home-section-head">
            <p>{{ $homeToursAcrossEgypt['eyebrow'] ?? 'Destinations & Themes' }}</p>
            <h2>{{ $homeToursAcrossEgypt['title'] ?? 'Tours Across Egypt' }}</h2>
        </div>

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
                    <span>{{ $activity->name }}</span>
                </a>
            @empty
                <p class="home-tours__empty" style="grid-column: 1 / -1; margin: 0; color: #5c6975;">
                    {{ __('No activities are available at the moment.') }}
                </p>
            @endforelse
        </div>
    </div>
</section>
