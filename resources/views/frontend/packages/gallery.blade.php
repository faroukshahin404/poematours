@extends('frontend.layouts.app')

@section('content')
    <section class="gallery-page">
        <div class="container gallery-page__head">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('packages.index') }}">Travel Destinations</a>
                <span>/</span>
                <span>Full Gallery</span>
            </nav>
            <h1>Egypt Destinations Gallery</h1>
            <p>Swipe through curated moments from Cairo, Luxor, Aswan, and beyond.</p>
        </div>

        <div class="container">
            <div class="gallery-swiper" data-gallery-swiper>
                <div class="gallery-swiper__stage">
                    @foreach($galleryImages as $image)
                        <figure class="gallery-swiper__slide {{ $loop->first ? 'is-active' : '' }}" data-gallery-slide>
                            <img src="{{ asset($image) }}" alt="Egypt destination gallery slide {{ $loop->iteration }}">
                        </figure>
                    @endforeach
                </div>

                <div class="gallery-swiper__controls">
                    <button type="button" class="gallery-swiper__nav" data-gallery-prev aria-label="Previous image">
                        <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 6l-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <span class="gallery-swiper__counter" data-gallery-counter>1 / {{ count($galleryImages) }}</span>
                    <button type="button" class="gallery-swiper__nav" data-gallery-next aria-label="Next image">
                        <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>

                <div class="gallery-swiper__thumbs">
                    @foreach($galleryImages as $image)
                        <button type="button" class="gallery-swiper__thumb {{ $loop->first ? 'is-active' : '' }}" data-gallery-thumb data-index="{{ $loop->index }}" aria-label="Show image {{ $loop->iteration }}">
                            <img src="{{ asset($image) }}" alt="Gallery thumbnail {{ $loop->iteration }}">
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
