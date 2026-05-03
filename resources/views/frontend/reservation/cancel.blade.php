@extends('frontend.layouts.app')

@section('content')
    <section class="customize-page">
        <div class="container">
            <div class="customize-card">
                <h1>Payment canceled</h1>
                <p>Your payment was not completed. You can return to the reservation page and try again.</p>
                @if ($reservationUuid !== '')
                    <p><strong>Reservation reference:</strong> {{ $reservationUuid }}</p>
                @endif
            </div>
        </div>
    </section>
@endsection
