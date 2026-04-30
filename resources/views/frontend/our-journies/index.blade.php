@extends('frontend.layouts.app')

@section('content')
    <div class="journeys-page">
    



    @if ($featuredBlog)
        <section class="journeys-featured section" data-journey-animate="section">
        <div class="container">
            <div class="journeys-section-head">
                <p>Editor's Pick</p>
                <h2>Featured Story</h2>
            </div>
            <article class="journeys-featured__card">
                <div class="journeys-featured__image">
                    <img src="{{ asset($featuredBlog['cover_image']) }}" alt="{{ $featuredBlog['title'] }}">
                </div>
                <div class="journeys-featured__content">
                    <span class="journeys-badge">{{ $featuredBlog['category'] }}</span>
                    <h3>{{ $featuredBlog['title'] }}</h3>
                    <p>{{ $featuredBlog['excerpt'] }}</p>
                    <a href="{{ route('our.journeys.show', $featuredBlog['slug']) }}" class="journeys-featured__cta">Read Story</a>
                </div>
            </article>
        </div>
    </section>
    @endif

    <section class="journeys-grid section" data-journey-animate="section">
        <div class="container">
            <div class="journeys-section-head">
                <p>Travel Journal</p>
                <h2>Discover More Journeys</h2>
            </div>
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
    </div>
@endsection
