<section class="hero">
    <div class="hero__slides" data-hero-slider>
        <div class="hero__slide is-active" data-hero-slide>
            <img class="hero__background" src="{{ asset('assets/images/placeholders/banner.jpeg') }}" alt="Golden sunrise over Egypt landscapes">
        </div>
        <div class="hero__slide" data-hero-slide>
            <img class="hero__background" src="{{ asset('assets/images/placeholders/sea-1.jpg') }}" alt="Travelers enjoying an Egyptian coastal landscape">
        </div>
        <div class="hero__slide" data-hero-slide>
            <img class="hero__background" src="{{ asset('assets/images/placeholders/sea-2.jpg') }}" alt="Travelers enjoying an Egyptian coastal landscape">
        </div>
    </div>
    <div class="hero__overlay"></div>

    <button type="button" class="hero__slider-btn hero__slider-btn--prev" data-hero-prev aria-label="Previous hero slide">
        <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M14.5 6.5L9 12l5.5 5.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    <button type="button" class="hero__slider-btn hero__slider-btn--next" data-hero-next aria-label="Next hero slide">
        <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M9.5 6.5L15 12l-5.5 5.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <div class="container hero__content hero__content--centered">
        <p class="hero__eyebrow">Experience Delight.</p>
        <h1 class="hero__title">
            We<span class="hero__title-apostrophe">&#8217;</span>ll take care of everything.
        </h1>
        <a href="{{ route('packages.index') }}" class="hero__cta">Design Your Tour</a>
       

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
                <li class="hero__trust-item">
                    <span class="hero__trust-icon" aria-hidden="true">
                        <svg class="hero__trust-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2.75 5.25 6.2v5.35c0 3.85 2.35 7.45 6.75 9.05 4.4-1.6 6.75-5.2 6.75-9.05V6.2L12 2.75Z" stroke="currentColor" stroke-width="1.35" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <div class="hero__trust-copy">
                        <span class="hero__trust-line">American-Egyptian</span>
                        <span class="hero__trust-line">Owned</span>
                    </div>
                </li>
                <li class="hero__trust-item">
                    <span class="hero__trust-icon" aria-hidden="true">
                        <svg class="hero__trust-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 4.75h10.25a1.75 1.75 0 0 1 1.75 1.75v11a1.75 1.75 0 0 1-1.75 1.75H9" stroke="currentColor" stroke-width="1.35" stroke-linejoin="round"/>
                            <path d="M9 4.75 6.25 3.35c-.9-.45-2.1.1-2.1 1.15v15c0 1.05 1.2 1.6 2.1 1.15L9 19.25" stroke="currentColor" stroke-width="1.35" stroke-linejoin="round"/>
                            <path d="M12.25 8.25h4.75M12.25 11.75h4.75M12.25 15.25h3.25" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <div class="hero__trust-copy">
                        <span class="hero__trust-line">Licensed</span>
                        <span class="hero__trust-line">Egyptologists</span>
                    </div>
                </li>
                <li class="hero__trust-item">
                    <span class="hero__trust-icon" aria-hidden="true">
                        <svg class="hero__trust-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="7.25" stroke="currentColor" stroke-width="1.35"/>
                            <path d="M12 4.85v2.35M12 16.8v2.35M19.05 12h-2.35M7.3 12H4.95M17.05 6.95 15.35 8.65M8.65 15.35 6.95 17.05M17.05 17.05 15.35 15.35M8.65 8.65 6.95 6.95" stroke="currentColor" stroke-width="1.15" stroke-linecap="round"/>
                            <path d="M12 8.5 14.35 14.5H9.65L12 8.5Z" stroke="currentColor" stroke-width="1.15" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <div class="hero__trust-copy">
                        <span class="hero__trust-line">Transparent</span>
                        <span class="hero__trust-line">Inclusions</span>
                    </div>
                </li>
                <li class="hero__trust-item">
                    <span class="hero__trust-icon" aria-hidden="true">
                        <svg class="hero__trust-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.35 4.55a4.85 4.85 0 0 0-6.85 0L12 5.95l-1.5-1.4a4.85 4.85 0 0 0-6.85 6.9l1.4 1.4L12 20.9l7.75-7.15 1.4-1.4a4.85 4.85 0 0 0 0-6.9Z" stroke="currentColor" stroke-width="1.35" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <div class="hero__trust-copy">
                        <span class="hero__trust-line">24/7 On-the-</span>
                        <span class="hero__trust-line">Ground Support</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>

@push('scripts')
@endpush
