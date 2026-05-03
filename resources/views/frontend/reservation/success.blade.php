@extends('frontend.layouts.app')

@section('content')
    <section class="customize-page">
        <div class="container">
            <div class="customize-card">
                <h1>Deposit received</h1>
                <p>Your reservation deposit was submitted successfully. Our team will contact you shortly.</p>
                @if ($reservationUuid !== '')
                    <p><strong>Reservation reference:</strong> {{ $reservationUuid }}</p>
                @endif
            </div>
        </div>
    </section>
@endsection
