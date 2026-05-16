<section class="section home-spirit" id="home-spirit">
    <div class="container">
        <header class="home-section-head home-section-head--editorial">
            <p class="home-section-head__eyebrow">{{ $homeSpirit['eyebrow'] ?? 'Our Signature Approach' }}</p>
            <h2>{{ $homeSpirit['title'] ?? 'Find your dream tour to Egypt here' }}</h2>
        </header>

        {{-- When the image column is enabled, remove `home-spirit__layout--text-only` from the layout div. --}}
        <div class="home-spirit__layout home-spirit__layout--text-only">
        {{-- <div class="home-spirit__image">
            <img src="{{ asset('assets/images/placeholders/template-2.avif') }}" alt="Poema Tours team by the sea">
        </div> --}}

        <div class="home-spirit__content">
            <p class="home-spirit__text">
                {{ $homeSpirit['body'] ?? '' }}
            </p>
        </div>
        </div>
    </div>
</section>
