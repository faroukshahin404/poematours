<section class="package-section" id="reviews">
    <div class="container package-reviews">
        <div class="package-reviews__head">
            <h2>Reviews</h2>
            <a href="{{ route('packages.reviews', $package['slug']) }}">Show All Reviews</a>
        </div>

        <div class="package-reviews__grid">
            @foreach(collect($details['reviews'])->take(4) as $review)
                <article class="review-card">
                    <div class="review-card__top">
                        <strong>{{ $review['name'] }}</strong>
                        <span>{{ $review['month_added'] }}</span>
                    </div>
                    <div class="review-card__rate" aria-label="Rating {{ $review['rate'] }} out of 5">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $review['rate'] ? 'is-filled' : '' }}">&#9733;</span>
                        @endfor
                    </div>
                    <p>{{ $review['comment'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
