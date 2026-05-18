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
                    <button type="button" class="package-details-hero__private-link" data-expert-open>
                        <svg class="package-details-hero__private-icon" viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.35"/>
                            <path d="M3.6 12h16.8M12 3.2a13.2 13.2 0 0 1 0 17.6" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round"/>
                        </svg>
                        <span>Want to take this journey privately? <span class="package-details-hero__private-underline">Learn more</span></span>
                    </button>
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
    @include('frontend.package-details.sections.why-choose')
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

    <div class="expert-modal" data-expert-modal @if(old('package_slug')) data-expert-auto-open="true" @endif aria-hidden="true">
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

                @if ($errors->any())
                    <div class="expert-form__notice expert-form__notice--error" role="alert">
                        <p>Please correct the errors below and try again.</p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="expert-form" action="{{ route('contact-leads.package-expert.store') }}" method="post" novalidate>
                    @csrf
                    <input type="hidden" name="package_title" value="{{ old('package_title', $package['title']) }}">
                    <input type="hidden" name="package_slug" value="{{ old('package_slug', $package['slug']) }}">

                    <div class="expert-form__grid">
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="First Name*" aria-label="First Name" required>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name*" aria-label="Last Name" required>
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number*" aria-label="Phone Number" required>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address*" aria-label="Email Address" required>
                    </div>

                    <div class="expert-form__radio-group">
                        <p>Are you a travel advisor?</p>
                        <label>
                            <input type="radio" name="is_travel_advisor" value="yes" @checked(old('is_travel_advisor', 'no') === 'yes')> Yes
                        </label>
                        <label>
                            <input type="radio" name="is_travel_advisor" value="no" @checked(old('is_travel_advisor', 'no') === 'no')> No
                        </label>
                    </div>

                    <label class="expert-form__label" for="tripPlan">Tell us more about your travel plans</label>
                    <textarea id="tripPlan" name="notes" rows="4" placeholder="Add a note" aria-label="Travel plans">{{ old('notes') }}</textarea>

                    <label class="expert-form__checkbox">
                        <input type="checkbox" name="privacy_accepted" value="1" @checked(old('privacy_accepted')) required>
                        I accept the <a href="{{ route('privacy.policy') }}" target="_blank" rel="noopener noreferrer">Privacy Policy</a>.
                    </label>
                    <label class="expert-form__checkbox">
                        <input type="checkbox" name="wants_newsletter" value="1" @checked(old('wants_newsletter'))>
                        I would like to receive news and updates.
                    </label>

                    <button type="submit" class="expert-form__submit">Speak to an Expert</button>
                </form>
            </div>
        </div>
    </div>

    @if (session('expert_enquiry_success'))
        <div
            class="expert-success-alert is-open"
            data-expert-success-alert
            role="alertdialog"
            aria-modal="true"
            aria-labelledby="expert-success-title"
            aria-hidden="false"
        >
            <div class="expert-success-alert__backdrop" data-expert-success-close tabindex="-1"></div>
            <div class="expert-success-alert__card">
                <div class="expert-success-alert__icon" aria-hidden="true">
                    <svg viewBox="0 0 52 52" width="52" height="52">
                        <circle class="expert-success-alert__icon-circle" cx="26" cy="26" r="24" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path class="expert-success-alert__icon-check" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M15 27l7 7 15-16"/>
                    </svg>
                </div>
                <p class="expert-success-alert__eyebrow">Enquiry received</p>
                <h2 id="expert-success-title" class="expert-success-alert__title">Awesome!</h2>
                <p class="expert-success-alert__message">
                    Your form has been submitted successfully. A travel expert will contact you within
                    <strong>24 hours</strong>.
                </p>
                <button type="button" class="expert-success-alert__btn" data-expert-success-close>Got it</button>
            </div>
        </div>
    @endif
@endsection
