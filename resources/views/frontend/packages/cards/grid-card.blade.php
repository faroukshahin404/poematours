<article class="package-card package-card--grid">
    <div class="package-card__media">
        <img src="{{ asset($package['image']) }}" alt="{{ $package['title'] }}">
        <div class="package-card__badges">
            <span>Small Group Journeys</span>
            <span class="is-offer">Offer</span>
        </div>
    </div>
    <div class="package-card__body">
        <h3>{{ $package['title'] }}</h3>
        <p class="package-card__season">2026 - 2027</p>
        <p class="package-card__subhead">{{ strtoupper($package['duration_days']) }} DAYS &middot; LIMITED TO 18 GUESTS</p>
        <div class="package-card__itinerary">
            <strong>Itinerary</strong>
            <span>Cairo &middot; Luxor &middot; Valley of the Kings &middot; Kom Ombo &middot; Aswan &middot; Abu Simbel</span>
        </div>
        <div class="package-card__bottom-row">
            <p class="package-card__from">From <strong>${{ number_format($package['price_after']) }}</strong> <span>per person.</span></p>
            <a href="{{ route('packages.show', $package['slug']) }}" class="package-card__button package-card__button--outline">View Journey</a>
        </div>
    </div>
</article>
