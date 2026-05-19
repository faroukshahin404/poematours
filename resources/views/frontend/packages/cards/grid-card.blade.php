<article class="package-card package-card--grid">
    <div class="package-card__media">
        <img src="{{ asset($package['image']) }}" alt="{{ $package['title'] }}">
        <div class="package-card__badges">
            @php
                $badgeLabel = $package['category_names'][0] ?? $package['label_names'][0] ?? null;
            @endphp
            @if($badgeLabel)
                <span>{{ $badgeLabel }}</span>
            @endif
            @if(!empty($package['has_offer']))
                <span class="is-offer">Offer</span>
            @endif
        </div>
    </div>
    <div class="package-card__body">
        <h3>{{ $package['title'] }}</h3>
        <p class="package-card__season">2026 - 2027</p>
        <p class="package-card__subhead">{{ strtoupper($package['duration_days'] . ' DAYS') }}</p>
        @if(!empty($package['itinerary_places']))
            <div class="package-card__itinerary">
                <strong>Itinerary</strong>
                <span>{{ implode(' · ', $package['itinerary_places']) }}</span>
            </div>
        @endif
        <div class="package-card__bottom-row">
            <p class="package-card__from">From <strong>${{ number_format($package['price_after']) }}</strong> <span>per person.</span></p>
            @include('frontend.packages.cards.partials.journey-actions', [
                'package' => $package,
                'journeyButtonClass' => 'package-card__button package-card__button--outline',
            ])
        </div>
    </div>
</article>
