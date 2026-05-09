@extends('frontend.layouts.app')

@section('content')
    @include('frontend.pages.legal.partials.page-shell', [
        'legal' => $legal,
    ])
@endsection
