@extends('frontend.layouts.app')

@section('content')
    @include('frontend.home.sections.hero')
    @include('frontend.home.sections.spirit')
    @include('frontend.home.sections.destinations')
    @include('frontend.home.sections.experiences')
    @include('frontend.home.sections.gallery')
    @include('frontend.home.sections.stories')
    @include('frontend.home.sections.cta')
@endsection
