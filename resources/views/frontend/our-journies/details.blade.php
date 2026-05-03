@extends('frontend.layouts.app')

@section('content')
    <section class="journeys-breadcrumb">
        <div class="container">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb"
            style="color: #000 !important;">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('our.journeys') }}">Our Journeys</a>
                <span>/</span>
                <span>{{ $blog['title'] }}</span>
            </nav>
        </div>
    </section>

    <section class="journey-details">
        <div class="container">
            <article class="journey-details__article">
                <header class="journey-details__header">
                    <span class="journeys-badge">{{ $blog['category'] }}</span>
                    <h1>{{ $blog['title'] }}</h1>
                    <p>{{ $blog['excerpt'] }}</p>
                </header>

                <div class="journey-slider" data-blog-slider>
                    <button type="button" class="journey-slider__nav journey-slider__nav--prev" data-blog-slider-prev aria-label="Previous image">&#8249;</button>
                    <div class="journey-slider__viewport">
                        @foreach ($blog['gallery'] as $image)
                            <figure class="journey-slider__slide {{ $loop->first ? 'is-active' : '' }}" data-blog-slide>
                                <img src="{{ asset($image) }}" alt="{{ $blog['title'] }} image {{ $loop->iteration }}">
                            </figure>
                        @endforeach
                    </div>
                    <button type="button" class="journey-slider__nav journey-slider__nav--next" data-blog-slider-next aria-label="Next image">&#8250;</button>
                </div>

                <div class="journey-details__content">
                    @foreach ($blog['content'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </article>
        </div>
    </section>
@endsection
