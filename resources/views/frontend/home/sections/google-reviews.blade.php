@php
    $googleReviews = $homeGoogleReviews ?? [];
    $reviewsUrl = $googleReviews['reviews_url'] ?? 'https://maps.app.goo.gl/w25ijRMzb43YDqZP9?g_st=ac';
    $reviewsList = collect($googleReviews['reviews'] ?? [])->take(3)->values();
    $reviewCount = $googleReviews['review_count'] ?? '247+';
    $rating = $googleReviews['rating'] ?? '4.9';
@endphp

<section
    class="section home-google-reviews"
    aria-labelledby="home-google-reviews-heading"
    data-home-google-reviews
>
    <div class="container">
        <div class="home-google-reviews__shell">
            <div class="home-google-reviews__main">
                <div class="home-google-reviews__visual home-reveal-item" style="--home-reveal-item-delay: 0ms;">
                    <div class="home-google-reviews__bubble" id="home-google-reviews-heading">
                        <span class="home-google-reviews__bubble-label">
                            {{ $googleReviews['title'] ?? __('Hear what our travelers have to say') }}
                        </span>
                        <span class="home-google-reviews__bubble-heart" aria-hidden="true">♥</span>
                    </div>

                    <figure class="home-google-reviews__figure">
                        <img
                            src="{{ asset($googleReviews['image'] ?? 'assets/images/placeholders/banner.jpeg') }}"
                            alt="{{ $googleReviews['image_alt'] ?? __('Travelers exploring Egypt with Poema Tours') }}"
                            loading="lazy"
                            decoding="async"
                            width="560"
                            height="680"
                        >
                        <figcaption class="home-google-reviews__stat">
                            <span class="home-google-reviews__stat-value">{{ $reviewCount }}</span>
                            <span class="home-google-reviews__stat-label">{{ __('Google reviews') }}</span>
                        </figcaption>
                        <div class="home-google-reviews__rating-pill" aria-label="{{ __('Rated :rating out of 5 on Google', ['rating' => $rating]) }}">
                            <svg class="home-google-reviews__google-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span>{{ $rating }}</span>
                            <span class="home-google-reviews__rating-stars" aria-hidden="true">★★★★★</span>
                        </div>
                    </figure>
                </div>

                <div
                    class="home-google-reviews__list home-reveal-item"
                    style="--home-reveal-item-delay: 140ms;"
                    role="list"
                    aria-label="{{ __('Recent Google reviews') }}"
                >
                    @forelse ($reviewsList as $index => $review)
                        @php
                            $name = (string) ($review['reviewer_name'] ?? $review['name'] ?? '');
                            $role = (string) ($review['reviewer_role'] ?? $review['role'] ?? '');
                            $comment = (string) ($review['comment'] ?? '');
                            $rate = min(5, max(0, (int) ($review['rate'] ?? 5)));
                            $initial = $name !== '' ? mb_strtoupper(mb_substr($name, 0, 1)) : '?';
                            $isActive = $index === 0;
                        @endphp
                        <article
                            class="home-google-reviews__card{{ $isActive ? ' is-active' : '' }}"
                            role="listitem"
                            tabindex="0"
                            data-home-google-review-card
                            data-review-index="{{ $index }}"
                            @if($isActive) aria-current="true" @endif
                        >
                            <div class="home-google-reviews__stars" aria-label="{{ __('Rating :n out of 5', ['n' => $rate]) }}">
                                @for ($star = 1; $star <= 5; $star++)
                                    <span class="{{ $star <= $rate ? 'is-filled' : '' }}" aria-hidden="true">★</span>
                                @endfor
                            </div>

                            <blockquote class="home-google-reviews__quote">
                                <p>{{ $comment }}</p>
                            </blockquote>

                            <footer class="home-google-reviews__author">
                                <span class="home-google-reviews__avatar" aria-hidden="true">{{ $initial }}</span>
                                <span class="home-google-reviews__meta">
                                    <strong>{{ $name }}</strong>
                                    @if($role !== '')
                                        <span>{{ $role }}</span>
                                    @endif
                                </span>
                            </footer>

                            <button
                                type="button"
                                class="home-google-reviews__card-action"
                                data-home-google-review-select
                                aria-label="{{ __('Show review from :name', ['name' => $name]) }}"
                            >
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </article>
                    @empty
                        <p class="home-google-reviews__empty">{{ __('No reviews to display yet.') }}</p>
                    @endforelse
                </div>
            </div>

            <footer class="home-google-reviews__cta-bar home-reveal-item" style="--home-reveal-item-delay: 220ms;">
                <p class="home-google-reviews__cta-text">
                    {{ $googleReviews['cta_text'] ?? __('Will you be our next happy traveler?') }}
                </p>
                <a
                    href="{{ $reviewsUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="home-google-reviews__cta-btn"
                >
                    {{ $googleReviews['cta_button'] ?? __('View on Google') }}
                </a>
            </footer>
        </div>
    </div>
</section>
