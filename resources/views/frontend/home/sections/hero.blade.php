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
            We&apos;ll take care of everything.
        </h1>
        <a href="{{ route('packages.index') }}" class="hero__cta">Design Your Tour</a>
       

        {{-- <a href="#" class="hero-trust-badge" aria-label="Read our Tripadvisor reviews">
            <span class="hero-trust-badge__brand">Tripadvisor</span>
            <span class="hero-trust-badge__name">Poema Tours</span>
            <span class="hero-trust-badge__rating" aria-hidden="true">●●●●●</span>
            <span class="hero-trust-badge__reviews">247 reviews</span>
        </a> --}}
    </div>
</section>

@push('scripts')
@endpush
