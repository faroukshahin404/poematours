@extends('frontend.layouts.app')



@section('content')
    <section class="reservation-page">
        <div class="reservation-shell">
            <div class="reservation-heading">
            <h1>Reservation Form</h1>
            <p>Please complete the reservation details. You can submit partial traveller and flight details now and update later.</p>
            </div>
        </div>
        <div class="reservation-shell">
            <div class="reservation-form-card">
                @if (session('status'))
                    <div class="customize-alert customize-alert--success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="customize-alert customize-alert--error">
                        Please review the highlighted fields and try again.
                    </div>
                @endif

                @include('frontend.reservation._form', [
                    'formAction' => route('reservation.store'),
                    'hiddenFields' => [],
                    'submitLabel' => 'Submit Reservation',
                    'submitClass' => 'customize-btn',
                    'showPaymentNote' => true,
                    'travellersDefaultCount' => 2,
                ])
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @include('frontend.reservation._form_scripts')
@endpush
