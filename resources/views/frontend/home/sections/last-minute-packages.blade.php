<section class="section home-last-minute-packages">
    <div class="container">
        @if(filled($homeLastMinute['eyebrow'] ?? null) || filled($homeLastMinute['title'] ?? null))
            <div class="home-section-head">
                @if(filled($homeLastMinute['eyebrow'] ?? null))
                    <p>{{ $homeLastMinute['eyebrow'] }}</p>
                @endif
                @if(filled($homeLastMinute['title'] ?? null))
                    <h2>{{ $homeLastMinute['title'] }}</h2>
                @endif
            </div>
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
