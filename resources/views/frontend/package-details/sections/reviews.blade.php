@php
    /** @var array<string, mixed> $package */
    /** @var array<string, mixed> $details */
    $reviewsList = collect($details['reviews'] ?? []);
@endphp

<section class="package-testimonials" id="reviews" aria-labelledby="package-testimonials-heading" data-package-testimonials-root>
    <div class="package-testimonials__inner">
        <div class="container package-testimonials__top">
            <p class="package-testimonials__kicker">{{ __('Testimonials') }}</p>
            <div class="package-testimonials__head-row">
                <div class="package-testimonials__title-wrap">
                    <h2 id="package-testimonials-heading" class="package-testimonials__title">
                        {{ __('Hear from travellers who have experienced Egypt with Poema Tours.') }}
                    </h2>

                </div>
                @if($reviewsList->isNotEmpty())
                    <div class="package-testimonials__nav" role="group" aria-label="{{ __('Scroll reviews') }}">
                        <button type="button" class="package-testimonials__nav-btn" data-testimonials-prev aria-label="{{ __('Previous reviews') }}">
                            <span aria-hidden="true">&#8592;</span>
                        </button>
                        <button type="button" class="package-testimonials__nav-btn" data-testimonials-next aria-label="{{ __('Next reviews') }}">
                            <span aria-hidden="true">&#8594;</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="container package-testimonials__carousel-wrap">
            @if($reviewsList->isEmpty())
                <p class="package-testimonials__empty">{{ __('No reviews available yet.') }}</p>
            @else
                <div class="package-testimonials__viewport">
                    <div class="package-testimonials__track" data-testimonials-track tabindex="0">
                        @foreach($reviewsList as $review)
                            @php
                                $name = (string) ($review['reviewer_name'] ?? '');
                                $address = trim((string) ($review['reviewer_address'] ?? ''));
                                $initial = $name !== '' ? mb_strtoupper(mb_substr($name, 0, 1)) : '?';
                                $rate = (int) ($review['rate'] ?? 0);
                            @endphp
                            <article class="package-testimonial-card">
                                <div class="package-testimonial-card__body">
                                    <p class="package-testimonial-card__comment">{{ $review['comment'] ?? '' }}</p>
                                    @if($rate > 0)
                                        <div class="package-testimonial-card__stars" aria-label="{{ __('Rating :n out of 5', ['n' => $rate]) }}">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $rate ? 'is-filled' : '' }}" aria-hidden="true">&#9733;</span>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                                <footer class="package-testimonial-card__footer">
                                    <div class="package-testimonial-card__avatar" aria-hidden="true">{{ $initial }}</div>
                                    <div class="package-testimonial-card__meta">
                                        <p class="package-testimonial-card__who">
                                            @if($address !== '')
                                                <strong>{{ $name }}</strong>
                                                <span class="package-testimonial-card__dash"> — </span>
                                                <strong>{{ $address }}</strong>
                                            @else
                                                <strong>{{ $name !== '' ? $name : __('Guest') }}</strong>
                                            @endif
                                        </p>
                                        <p class="package-testimonial-card__trip">
                                            <em>{{ $package['title'] }} — {{ $selectedYear }}</em>
                                            @if(! empty($review['month_added']))
                                                <span class="package-testimonial-card__date"> · {{ $review['month_added'] }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </footer>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

       
    </div>
</section>
