<section class="section home-last-minute-packages">
    <div class="container">
        <p class="home-last-minute-packages__eyebrow">Last Minute Packages</p>
        <h2 class="section-title">Ready to leave soon? Pick your next Egypt escape.</h2>

        <div class="home-last-minute-packages__grid">
            @forelse (($lastMinutePackages ?? []) as $package)
                @include('frontend.packages.cards.grid-card', ['package' => $package])
            @empty
                <p class="home-last-minute-packages__empty">No packages are available at the moment.</p>
            @endforelse
        </div>
    </div>
</section>
