@extends('frontend.layouts.app')

@section('content')
    <section class="package-details-hero">
        <img src="{{ asset($details['hero_image']) }}" alt="{{ $package['title'] }}">
        <div class="package-details-hero__overlay"></div>
        <div class="container package-details-hero__content">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('packages.index') }}">Travel Destinations</a>
                <span>/</span>
                <span>Packages</span>
                <span>/</span>
                <span>{{ $package['title'] }}</span>
            </nav>
            <h1>{{ $package['title'] }}</h1>
        </div>
    </section>

    @include('frontend.package-details.sections.stats')

    <nav class="package-details-nav" data-package-nav>
        <div class="container package-details-nav__inner">
            <a href="#overview">Overview</a>
            <a href="#itinerary">Itinerary</a>
            <a href="#ship">About the ship</a>
            <a href="#dates-prices">Dates & Prices</a>
            <a href="#essential-info">Essential Info</a>
            <a href="#reviews">Reviews</a>
        </div>
    </nav>

    @include('frontend.package-details.sections.overview')
    @include('frontend.package-details.sections.itinerary')
    @include('frontend.package-details.sections.ship')
    @include('frontend.package-details.sections.dates-prices')
    @include('frontend.package-details.sections.essential-info')
    @include('frontend.package-details.sections.reviews')
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

    <aside class="hotel-modal" data-hotel-modal aria-hidden="true">
        <div class="hotel-modal__panel">
            <button class="hotel-modal__close" type="button" data-hotel-modal-close aria-label="Close hotel details">&times;</button>
            <img src="" alt="Hotel preview" data-hotel-image>
            <h3 data-hotel-title>Hotel details</h3>
            <p data-hotel-description></p>
            <ul>
                <li><strong>Price:</strong> <span data-hotel-price></span></li>
                <li><strong>Single supplement:</strong> <span data-hotel-supplement></span></li>
                <li><strong>Cabin:</strong> <span data-hotel-cabin></span></li>
            </ul>
        </div>
    </aside>

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
