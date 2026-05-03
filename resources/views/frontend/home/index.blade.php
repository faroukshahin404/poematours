@extends('frontend.layouts.app')

@section('content')
    <div class="home-reveal home-reveal--visible" data-home-reveal>
        @include('frontend.home.sections.hero')
    </div>
    
    <div class="home-reveal" data-home-reveal data-home-reveal-delay="90">
        @include('frontend.home.sections.spirit')
    </div>
 
    {{-- tours accross egypt --}}
    <div class="home-reveal" data-home-reveal data-home-reveal-delay="140">
        @include('frontend.home.sections.tours-across-egypt')
    </div>
    <div class="home-reveal" data-home-reveal data-home-reveal-delay="180">
        @include('frontend.home.sections.last-minute-packages')
    </div>
   
    {{-- <div class="home-reveal" data-home-reveal data-home-reveal-delay="180">
        @include('frontend.home.sections.experiences')
    </div> --}}
    {{-- <div class="home-reveal" data-home-reveal data-home-reveal-delay="210">
        @include('frontend.home.sections.gallery')
    </div> --}}
    <div class="home-reveal" data-home-reveal data-home-reveal-delay="240">
        @include('frontend.home.sections.stories')
    </div>
    <div class="home-reveal" data-home-reveal data-home-reveal-delay="260">
        @include('frontend.home.sections.why-poema')
    </div>
    {{-- <div class="home-reveal" data-home-reveal data-home-reveal-delay="270">
        @include('frontend.home.sections.cta')
    </div> --}}
@endsection
