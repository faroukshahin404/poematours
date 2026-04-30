<article class="package-card package-card--list">
    <div class="package-card__media">
        <img src="{{ asset($package['image']) }}" alt="{{ $package['title'] }}">
        <div class="package-card__badges">
            <span>Small Group Journeys</span>
            <span class="is-offer">Offer</span>
        </div>
    </div>
    <div class="package-card__body">
        <h3>{{ $package['title'] }}</h3>
        <p class="package-card__subhead">{{ strtoupper($package['duration_days'] . ' DAYS') }} &middot; LIMITED TO 18 GUESTS</p>
        <p>{{ $package['description'] }}</p>
        <div class="package-card__itinerary">
            <strong>Itinerary</strong>
            <span>Cairo &middot; Luxor &middot; Valley of the Kings &middot; Aswan</span>
        </div>
    </div>
    <div class="package-card__side">
        <div class="package-card__rating">
            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l2.7 5.4 6 .9-4.3 4.2 1 5.9L12 16.5 6.6 19.4l1-5.9L3.3 9.3l6-.9z" fill="currentColor"/></svg>
            {{ $package['rating'] }} ({{ $package['reviews_count'] }} reviews)
        </div>
        <div class="package-card__bottom-row">
            <p class="package-card__from">From <strong>${{ number_format($package['price_after']) }}</strong> per person.</p>
            <a href="{{ route('packages.show', $package['slug']) }}" class="package-card__button package-card__button--dark">View Journey</a>
        </div>
    </div>
</article>
