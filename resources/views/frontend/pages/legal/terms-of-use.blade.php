@extends('frontend.layouts.app')

@section('content')
    <section class="legal-page">
        <div class="container legal-page__container">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">{{ $legal['breadcrumb_home_label'] ?? 'Home' }}</a>
                <span>/</span>
                <span>{{ $legal['breadcrumb_current_label'] ?? 'Terms of Use' }}</span>
            </nav>
            <h1>{{ $legal['title'] ?? 'Terms of Use' }}</h1>
            <div>{!! $legal['body'] ?? '' !!}</div>
        </div>
    </section>
@endsection
