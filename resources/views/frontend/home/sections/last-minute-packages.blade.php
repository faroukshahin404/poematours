<section class="section home-last-minute-packages">
    <div class="container">
        <div class="home-section-head">
            <p>{{ $homeLastMinute['eyebrow'] ?? 'Curated Now' }}</p>
            <h2>{{ $homeLastMinute['title'] ?? 'Last Minute Packages' }}</h2>
        </div>
        <div class="home-last-minute-packages__grid">
            @forelse (($lastMinutePackages ?? []) as $package)
                @include('frontend.packages.cards.grid-card', ['package' => $package])
            @empty
                <p class="home-last-minute-packages__empty">
                    {{ $homeLastMinute['empty_state'] ?? 'No packages are available at the moment.' }}
                </p>
            @endforelse
        </div>
    </div>
</section>
