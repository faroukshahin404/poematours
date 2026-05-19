@extends('frontend.layouts.app')

@section('content')
    <main class="home-page home-page--hero-header" id="home-page">
        <div class="home-page__hero home-reveal home-reveal--visible" data-home-reveal data-home-hero>
            @include('frontend.home.sections.hero')
        </div>

        <div class="home-page__divider" aria-hidden="true">
            <span class="home-page__divider-line"></span>
            <span class="home-page__divider-mark"></span>
            <span class="home-page__divider-line"></span>
        </div>

        <div class="home-page__band home-reveal" data-home-reveal data-home-reveal-delay="90">
            @include('frontend.home.sections.spirit')
        </div>

        <div class="home-page__divider home-page__divider--subtle" aria-hidden="true">
            <span class="home-page__divider-line"></span>
        </div>

        <div class="home-page__band home-page__band--elevated home-reveal" data-home-reveal data-home-reveal-delay="120">
            @include('frontend.home.sections.destinations')
        </div>

        <div class="home-page__band home-reveal" data-home-reveal data-home-reveal-delay="140">
            @include('frontend.home.sections.tours-across-egypt')
        </div>

        <div class="home-page__divider home-page__divider--subtle" aria-hidden="true">
            <span class="home-page__divider-line"></span>
        </div>

        <div class="home-page__band home-page__band--elevated home-reveal" data-home-reveal data-home-reveal-delay="180">
            @include('frontend.home.sections.last-minute-packages')
        </div>

        <div class="home-page__band home-reveal" data-home-reveal data-home-reveal-delay="220">
            @include('frontend.home.sections.stories')
        </div>

        <div class="home-page__divider home-page__divider--subtle" aria-hidden="true">
            <span class="home-page__divider-line"></span>
        </div>

        <div class="home-page__band home-page__band--elevated home-reveal" data-home-reveal data-home-reveal-delay="240">
            @include('frontend.home.sections.google-reviews')
        </div>

        <div class="home-page__divider" aria-hidden="true">
            <span class="home-page__divider-line"></span>
            <span class="home-page__divider-mark"></span>
            <span class="home-page__divider-line"></span>
        </div>

        <div class="home-page__band home-page__band--closing home-reveal" data-home-reveal data-home-reveal-delay="260">
            @include('frontend.home.sections.why-poema')
        </div>

        <div class="home-page__band home-page__band--cta home-reveal" data-home-reveal data-home-reveal-delay="300">
            @include('frontend.home.sections.cta')
        </div>
    </main>
@endsection
