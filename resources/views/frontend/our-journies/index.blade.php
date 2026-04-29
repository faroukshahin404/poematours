@extends('frontend.layouts.app')

@section('content')
    <section class="journeys-breadcrumb" data-journey-animate="section">
        <div class="container">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Our Journeys</span>
            </nav>
        </div>
    </section>

    <section class="journeys-hero" data-journey-animate="section">
        <div class="container">
            <h1>Our Journeys</h1>
            <p>Stories, guides, and inspiration crafted for modern travelers exploring Egypt.</p>
        </div>
    </section>

    @if ($featuredBlog)
        <section class="journeys-featured" data-journey-animate="section">
        <div class="container">
            <article class="journeys-featured__card">
                <div class="journeys-featured__image">
                    <img src="{{ asset($featuredBlog['cover_image']) }}" alt="{{ $featuredBlog['title'] }}">
                </div>
                <div class="journeys-featured__content">
                    <span class="journeys-badge">{{ $featuredBlog['category'] }}</span>
                    <h2>{{ $featuredBlog['title'] }}</h2>
                    <p>{{ $featuredBlog['excerpt'] }}</p>
                    <a href="{{ route('our.journeys.show', $featuredBlog['slug']) }}" class="btn btn--primary">View Details</a>
                </div>
            </article>
        </div>
    </section>
    @endif

    <section class="journeys-grid" data-journey-animate="section">
        <div class="container">
            <div class="journeys-filter" role="toolbar" aria-label="Blog categories" data-journey-animate="item">
                @foreach ($categories as $category)
                    <button
                        type="button"
                        class="journeys-filter__btn {{ $loop->first ? 'is-active' : '' }}"
                        data-journey-filter="{{ strtolower($category) }}"
                    >
                        {{ $category }}
                    </button>
                @endforeach
            </div>

            <div class="journeys-grid__list" data-journey-cards data-journey-animate="item">
                @foreach ($journeyBlogs as $blog)
                @include('frontend.our-journies.blog-card', ['blog' => $blog])
                @endforeach
            </div>
        </div>
    </section>
@endsection
