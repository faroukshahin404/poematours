@extends('frontend.layouts.app')

@section('content')
    <section class="package-details-hero package-details-hero--immersive">
        <div class="package-details-hero__media">
            <img
                class="package-details-hero__img"
                src="{{ asset($details['hero_image']) }}"
                alt="{{ $package['title'] }}"
                width="1920"
                height="1080"
                fetchpriority="high"
            >
            <div class="package-details-hero__overlay" aria-hidden="true"></div>

          

            <div class="container package-details-hero__intro">
                <div class="package-details-hero__badges">
                    <span class="package-details-hero__badge package-details-hero__badge--light">{{ $package['category_names'][0] ?? __('Journey') }}</span>
                    @if(($package['price_before'] ?? 0) > ($package['price_after'] ?? 0))
                        <span class="package-details-hero__badge package-details-hero__badge--accent">Offer</span>
                    @endif
                </div>
                <h1 class="package-details-hero__title">
                    <span class="package-details-hero__title-text">{{ $package['title'] }} {{ $selectedYear }}</span>
                    
                </h1>
               
            </div>
        </div>

        <div class="package-details-hero__stats-strip">
            
            <div class="container package-details-hero__stats-strip-inner">
                @include('frontend.package-details.sections.stats', ['immersive' => true])
                <p class="package-details-hero__private">
                    <a href="mailto:hello@poematours.com?subject={{ rawurlencode('Private journey — '.$package['title']) }}" class="package-details-hero__private-link">
                        <svg class="package-details-hero__private-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.35"/>
                            <path d="M3.6 12h16.8M12 3.2a13.2 13.2 0 0 1 0 17.6" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round"/>
                        </svg>
                        <span>Want to take this journey privately? <span class="package-details-hero__private-underline">Learn more</span></span>
                    </a>
                </p>
            </div>
        </div>
    </section>

    <nav class="package-details-nav" data-package-nav data-sticky-subnav>
        <div class="container package-details-nav__inner">
            <a href="#overview">{{ $details['labels']['overview'] ?? __('Overview') }}</a>
            <a href="#itinerary">{{ $details['labels']['itinerary'] ?? __('Itinerary') }}</a>
            <a href="#ship">{{ $details['labels']['ship'] ?? __('About the ship') }}</a>
            <a href="#dates-prices">{{ $details['labels']['dates_prices'] ?? __('Dates & Prices') }}</a>
            <a href="#essential-info">{{ $details['labels']['essential_info'] ?? __('Essential Info') }}</a>
            <a href="#reviews">{{ $details['labels']['reviews'] ?? __('Reviews') }}</a>
        </div>
    </nav>

    @include('frontend.package-details.sections.overview')
    @include('frontend.package-details.sections.itinerary')
    @include('frontend.package-details.sections.ship')
    @include('frontend.package-details.sections.dates-prices')
    @include('frontend.package-details.sections.reviews')
    @include('frontend.package-details.sections.essential-info')
    @include('frontend.package-details.sections.related-packages')

    <div class="expert-floating-bar" data-expert-floating>
        <div class="expert-floating-bar__summary">
            <span>{{ $package['duration_days'] }} Days</span>
            <span>from ${{ number_format($package['price_after']) }}</span>
            <span>{{ $details['max_guests'] }} guests</span>
        </div>
        <button type="button" class="expert-floating-bar__cta" data-expert-open>Speak to an Expert</button>
    </div>

    <div class="image-lightbox" data-image-lightbox aria-hidden="true">
        <button class="image-lightbox__close" type="button" data-lightbox-close aria-label="Close image viewer">&times;</button>
        <button class="image-lightbox__nav image-lightbox__nav--prev" type="button" data-lightbox-prev aria-label="Previous image">&#8249;</button>
        <div class="image-lightbox__stage">
            <img src="" alt="Gallery preview" data-lightbox-image>
        </div>
        <button class="image-lightbox__nav image-lightbox__nav--next" type="button" data-lightbox-next aria-label="Next image">&#8250;</button>
        <div class="image-lightbox__controls">
            <button type="button" data-lightbox-zoom-in>+</button>
            <button type="button" data-lightbox-zoom-out>-</button>
        </div>
    </div>

    <div class="hotel-drawer" data-hotel-modal aria-hidden="true">
        <div class="hotel-drawer__backdrop" data-hotel-modal-close tabindex="-1"></div>
        <aside
            class="hotel-drawer__sheet"
            role="dialog"
            aria-modal="true"
            aria-labelledby="hotel-drawer-suite-label"
        >
            <button class="hotel-drawer__close" type="button" data-hotel-modal-close aria-label="Close suite details">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="hotel-drawer__scroll">
                <div class="hotel-drawer__hero">
                    <img src="" alt="" class="hotel-drawer__hero-img" data-hotel-image width="800" height="520">
                    <button
                        type="button"
                        class="hotel-drawer__gallery-trigger"
                        data-hotel-gallery-open
                        aria-label="Open photo gallery"
                    >
                        <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="3" y="3" width="9" height="9" rx="1" fill="none" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="12" y="12" width="9" height="9" rx="1" fill="none" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="12" y="3" width="9" height="9" rx="1" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                    </button>
                </div>

                <div class="hotel-drawer__inner">
                    <p id="hotel-drawer-suite-label" class="hotel-drawer__suite-name" data-hotel-suite-name></p>
                    <p class="hotel-drawer__description" data-hotel-description></p>

                    <dl class="hotel-drawer__specs">
                        <div class="hotel-drawer__spec-row">
                            <dt>Price</dt>
                            <dd data-hotel-price></dd>
                        </div>
                        <div class="hotel-drawer__spec-row">
                            <dt>Single Supplement</dt>
                            <dd data-hotel-supplement></dd>
                        </div>
                        <div class="hotel-drawer__spec-row">
                            <dt>Cabin</dt>
                            <dd data-hotel-cabin></dd>
                        </div>
                    </dl>

                    <hr class="hotel-drawer__rule">

                    <div class="hotel-drawer__accordion" data-hotel-accordion hidden></div>
                </div>
            </div>
        </aside>
    </div>

    <div class="expert-modal" data-expert-modal aria-hidden="true">
        <div class="expert-modal__backdrop" data-expert-close></div>
        <div class="expert-modal__dialog">
            <div class="expert-modal__media">
                <img src="{{ asset($details['hero_image']) }}" alt="{{ $package['title'] }}">
                <div class="expert-modal__media-content">
                    <p>Small Group Journeys</p>
                    <h3>{{ $package['title'] }}</h3>
                    <span>{{ $package['duration_days'] }} Days - Limited to {{ $details['max_guests'] }} Guests</span>
                    <strong>From ${{ number_format($package['price_after']) }} per person</strong>
                </div>
            </div>
            <div class="expert-modal__form-side">
                <button type="button" class="expert-modal__close" data-expert-close aria-label="Close enquiry form">&times;</button>
                <h2>Enquiry Form</h2>
                <form class="expert-form" action="#" method="POST">
                    <div class="expert-form__grid">
                        <input type="text" placeholder="First Name*" required>
                        <input type="text" placeholder="Last Name*" required>
                        <input type="tel" placeholder="Phone Number*" required>
                        <input type="email" placeholder="Email Address*" required>
                    </div>
                    <div class="expert-form__radio-group">
                        <p>Are you a travel advisor?</p>
                        <label><input type="radio" name="advisor" value="yes"> Yes</label>
                        <label><input type="radio" name="advisor" value="no" checked> No</label>
                    </div>
                    <label class="expert-form__label" for="tripPlan">Tell us more about your travel plans</label>
                    <textarea id="tripPlan" rows="4" placeholder="Add a note"></textarea>
                    <label class="expert-form__checkbox"><input type="checkbox" checked> I accept the Privacy Policy.</label>
                    <label class="expert-form__checkbox"><input type="checkbox"> I would like to receive news and updates.</label>
                    <button type="submit" class="expert-form__submit">Speak to an Expert</button>
                </form>
            </div>
        </div>
    </div>
@endsection
