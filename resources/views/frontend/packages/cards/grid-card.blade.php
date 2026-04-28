<article class="package-card package-card--grid">
    <img src="{{ asset($package['image']) }}" alt="{{ $package['title'] }}">
    <div class="package-card__body">
        <h3>{{ $package['title'] }}</h3>
        <p>{{ $package['description'] }}</p>
        <div class="package-card__meta">
            <span>
                <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v3M16 2v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                {{ $package['duration_days'] }} days
            </span>
            <span>
                <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3c4.4 0 8 3.4 8 7.6 0 5.5-6.8 9.8-7.1 10-.6.4-1.2.4-1.8 0-.3-.2-7.1-4.5-7.1-10C4 6.4 7.6 3 12 3zm0 4a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" fill="currentColor"/></svg>
                {{ $package['group_name'] }}
            </span>
        </div>
        <div class="package-card__price">
            <del>${{ number_format($package['price_before']) }}</del>
            <strong>${{ number_format($package['price_after']) }}</strong>
        </div>
        <div class="package-card__rating">
            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l2.7 5.4 6 .9-4.3 4.2 1 5.9L12 16.5 6.6 19.4l1-5.9L3.3 9.3l6-.9z" fill="currentColor"/></svg>
            {{ $package['rating'] }} ({{ $package['reviews_count'] }} reviews)
        </div>
        <a href="{{ route('packages.show', $package['slug']) }}" class="package-card__button">View Details</a>
    </div>
</article>
