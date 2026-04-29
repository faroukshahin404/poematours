@extends('frontend.layouts.app')

@section('content')
    <div class="home-reveal home-reveal--visible" data-home-reveal>
        <section class="about-hero" style="margin-bottom: 10px;">
            <div class="container">
                <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <span>About Us</span>
                </nav>
                <h1>Poema Tours Company Profile</h1>
                <p>Crafting elevated travel experiences across Egypt with heritage, care, and detail.</p>
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
