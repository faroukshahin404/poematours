@extends('frontend.layouts.app')

@section('content')
    <section class="gallery-page package-testimonials package-testimonials--page">
        <div class="container gallery-page__head">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('packages.index') }}">Travel Destinations</a>
                <span>/</span>
                <a href="{{ route('packages.show', $package['slug']) }}">{{ $package['title'] }}</a>
                <span>/</span>
                <span>Reviews</span>
            </nav>
            <p class="package-testimonials__kicker package-testimonials__kicker--spaced">{{ __('Testimonials') }}</p>
            <h1 class="package-testimonials__title package-testimonials__title--page">{{ __('All guest reviews') }}</h1>
            <p class="package-testimonials__page-intro">{{ __('Guest feedback for this journey.') }}</p>
        </div>

        <div class="container package-testimonials__page-grid">
            @forelse(collect($reviews) as $review)
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
            @empty
                <p class="package-testimonials__empty">{{ __('No reviews available yet.') }}</p>
            @endforelse
        </div>
    </section>
@endsection
