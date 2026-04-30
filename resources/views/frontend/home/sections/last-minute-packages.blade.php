<section class="section home-last-minute-packages">
    <div class="container">
        <div class="home-section-head">
            <p>Curated Now</p>
            <h2>Last Minute Packages</h2>
        </div>
        <div class="home-last-minute-packages__grid">
            @forelse (($lastMinutePackages ?? []) as $package)
                @include('frontend.packages.cards.grid-card', ['package' => $package])
            @empty
                <p class="home-last-minute-packages__empty">No packages are available at the moment.</p>
            @endforelse
        </div>
    </div>
</section>
