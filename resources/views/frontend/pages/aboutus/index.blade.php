@extends('frontend.layouts.app')

@section('content')
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

   @include('frontend.pages.aboutus.sections.about-welcome')
   @include('frontend.pages.aboutus.sections.reels')
   @include('frontend.pages.aboutus.sections.services')
   @include('frontend.pages.aboutus.sections.gallery')
   @include('frontend.pages.aboutus.sections.latest-blogs')
    
@endsection
