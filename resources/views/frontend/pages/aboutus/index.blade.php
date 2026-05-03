@extends('frontend.layouts.app')

@section('content')
    <div class="home-reveal home-reveal--visible" data-home-reveal>
        <section class="about-hero" style="margin-bottom: 10px;">
            <div class="container">

                <h1>{{ $aboutHero['title'] ?? 'Poema Tours Company Profile' }}</h1>
                <p>{{ $aboutHero['subtitle'] ?? '' }}</p>
            </div>
        </section>
    </div>

    <div class="home-reveal" data-home-reveal data-home-reveal-delay="90">
        @include('frontend.pages.aboutus.sections.about-welcome')
    </div>
    <div class="home-reveal" data-home-reveal data-home-reveal-delay="130">
        @include('frontend.pages.aboutus.sections.reels')
    </div>
    <div class="home-reveal" data-home-reveal data-home-reveal-delay="170">
        @include('frontend.pages.aboutus.sections.services')
    </div>
    <div class="home-reveal" data-home-reveal data-home-reveal-delay="210">
        @include('frontend.pages.aboutus.sections.gallery')
    </div>
    <div class="home-reveal" data-home-reveal data-home-reveal-delay="250">
        @include('frontend.pages.aboutus.sections.latest-blogs')
    </div>
    
@endsection
