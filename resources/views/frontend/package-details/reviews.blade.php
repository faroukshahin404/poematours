@extends('frontend.layouts.app')

@section('content')
    <section class="gallery-page">
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
            <h1>All Reviews</h1>
            <p>Guest feedback from recent departures.</p>
        </div>

        <div class="container package-reviews__grid package-reviews__grid--full">
            @foreach($reviews as $review)
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
    </section>
@endsection
