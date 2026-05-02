@extends('frontend.layouts.app')

@section('content')
    <div class="destinations-page">
        <section class="destinations-page__intro section" aria-labelledby="destinations-page-title">
            <div class="container">
                <p class="destinations-page__eyebrow">Destinations in Egypt</p>
                <h1 id="destinations-page-title" class="section-title">Where ancient wonder meets the sea</h1>
                <p class="section-subtitle destinations-page__lede">
                    Explore cities, oases, and coastlines — each place tells a different chapter of Egypt. Select a
                    destination to browse journeys that begin there.
                </p>
            </div>
        </section>

        <section class="destinations-page__gallery-wrap section" aria-label="Egypt destination gallery">
            <div class="container">
                <div class="destinations-masonry">
                    @foreach ($destinations as $place)
                        <a
                            href="{{ !empty($place['packages_query']) ? route('packages.index', $place['packages_query']) : route('packages.index') }}"
                            class="destinations-masonry__card destinations-masonry__card--{{ $place['size'] }}"
                        >
                            <span class="destinations-masonry__media">
                                <img
                                    src="{{ asset($place['image']) }}"
                                    alt=""
                                    class="destinations-masonry__img"
                                    loading="lazy"
                                    decoding="async"
                                >
                                <span class="destinations-masonry__overlay" aria-hidden="true"></span>
                            </span>
                            <span class="destinations-masonry__label">{{ $place['name'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
