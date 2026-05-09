@extends('frontend.layouts.app')

@section('content')
    <section class="legal-page">
        <div class="container legal-page__container">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>{{ $cmsPage->name }}</span>
            </nav>
            <h1>{{ $cmsPage->name }}</h1>
            <div>{!! $cmsPage->body !!}</div>
        </div>
    </section>
@endsection
