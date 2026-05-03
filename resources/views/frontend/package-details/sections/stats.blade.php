@php
    $immersive = filter_var($immersive ?? false, FILTER_VALIDATE_BOOLEAN);
@endphp
@if ($immersive)
    <div class="package-details-hero__stats-grid" role="group" aria-label="Trip summary statistics">
        <article class="package-details-hero__stat">
            <strong>{{ $package['duration_days'] }}</strong>
            <span class="package-details-hero__stat-label">Days</span>
        </article>
        <article class="package-details-hero__stat">
            <strong>{{ $details['count_destinations'] }}</strong>
            <span class="package-details-hero__stat-label">Destinations</span>
        </article>
        <article class="package-details-hero__stat">
            <strong>{{ $details['max_guests'] }}</strong>
            <span class="package-details-hero__stat-label">Guests (max)</span>
        </article>
        <article class="package-details-hero__stat">
            <strong>{{ $details['available_places'] }}</strong>
            <span class="package-details-hero__stat-label">Available places</span>
        </article>
    </div>
@else
    <section class="package-stats section">
        <div class="container package-stats__grid">
            <article class="package-stat">
                <strong>{{ $package['duration_days'] }}</strong>
                <span class="label">Days</span>
            </article>
            <article class="package-stat">
                <strong>{{ $details['count_destinations'] }}</strong>
                <span class="label">Destinations</span>
            </article>
            <article class="package-stat">
                <strong>{{ $details['max_guests'] }}</strong>
                <span class="label">Max Guests</span>
            </article>
            <article class="package-stat">
                <strong>{{ $details['available_places'] }}</strong>
                <span class="label">Available Places</span>
            </article>
        </div>
    </section>
@endif
