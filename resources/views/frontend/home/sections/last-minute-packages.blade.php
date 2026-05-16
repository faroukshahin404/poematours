<section class="section home-last-minute-packages">
    <div class="container">
        @if(filled($homeLastMinute['eyebrow'] ?? null) || filled($homeLastMinute['title'] ?? null))
            <header class="home-section-head home-section-head--editorial">
                @if(filled($homeLastMinute['eyebrow'] ?? null))
                    <p class="home-section-head__eyebrow">{{ $homeLastMinute['eyebrow'] }}</p>
                @endif
                @if(filled($homeLastMinute['title'] ?? null))
                    <h2>{{ $homeLastMinute['title'] }}</h2>
                @endif
            </header>
        @endif
        <div class="home-last-minute-packages__grid">
            @forelse ($lastMinutePackages as $package)
                @include('frontend.packages.cards.grid-card', ['package' => $package])
            @empty
                @if(filled($homeLastMinute['empty_state'] ?? null))
                    <p class="home-last-minute-packages__empty">{{ $homeLastMinute['empty_state'] }}</p>
                @endif
            @endforelse
        </div>
    </div>
</section>
