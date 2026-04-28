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
