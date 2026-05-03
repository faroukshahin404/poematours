@extends('frontend.layouts.app')

@section('content')
    <section class="legal-page">
        <div class="container legal-page__container">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">{{ $legal['breadcrumb_home_label'] ?? 'Home' }}</a>
                <span>/</span>
                <span>{{ $legal['breadcrumb_current_label'] ?? 'Privacy Policy' }}</span>
            </nav>
            <h1>{{ $legal['title'] ?? 'Privacy Policy' }}</h1>
            <div>{!! $legal['body'] ?? '' !!}</div>
        </div>
    </section>
@endsection
