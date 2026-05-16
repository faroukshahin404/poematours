<section class="hero">
    <div class="hero__slides">
        <div class="hero__slide is-active">
            <img
                class="hero__background"
                src="{{ asset($homeHero['background_image'] ?? 'assets/images/placeholders/banner.jpeg') }}"
                alt="{{ $homeHero['background_image_alt'] ?? 'Golden sunrise over Egypt landscapes' }}"
            >
        </div>
    </div>
    <div class="hero__overlay"></div>

    <div class="container hero__content hero__content--centered">
        <p class="hero__eyebrow">{{ $homeHero['eyebrow'] ?? 'Experience Delight.' }}</p>
        <h1 class="hero__title">
            {{ $homeHero['title'] ?? "We'll take care of everything." }}
        </h1>
        <a href="{{ url($homeHero['cta_url'] ?? '/packages') }}" class="hero__cta">
            {{ $homeHero['cta_text'] ?? 'Design Your Tour' }}
        </a>

      

        {{-- <a href="#" class="hero-trust-badge" aria-label="Read our Tripadvisor reviews">
            <span class="hero-trust-badge__brand">Tripadvisor</span>
            <span class="hero-trust-badge__name">Poema Tours</span>
            <span class="hero-trust-badge__rating" aria-hidden="true">●●●●●</span>
            <span class="hero-trust-badge__reviews">247 reviews</span>
        </a> --}}
    </div>

    <div class="hero__trust-bar" role="region" aria-label="Why travel with Poema Tours">
        <div class="container">
            <ul class="hero__trust-grid">
                @foreach (($homeHero['trust_items'] ?? []) as $item)
                    <li class="hero__trust-item">
                        <span class="hero__trust-icon" aria-hidden="true">
                            <svg class="hero__trust-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.75 5.25 6.2v5.35c0 3.85 2.35 7.45 6.75 9.05 4.4-1.6 6.75-5.2 6.75-9.05V6.2L12 2.75Z" stroke="currentColor" stroke-width="1.35" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <div class="hero__trust-copy">
                            <span class="hero__trust-line">{{ $item['line_1'] ?? '' }}</span>
                            <span class="hero__trust-line">{{ $item['line_2'] ?? '' }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

@push('scripts')
@endpush
