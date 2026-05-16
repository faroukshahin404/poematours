@extends('frontend.layouts.app')

@push('styles')
    <style>
        .payment-failure-page {
            background: var(--color-009);
            min-height: calc(100vh - var(--header-offset, var(--header-total-height)));
        }

        .payment-failure-card {
            text-align: center;
        }

        .payment-failure-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #fee2e2;
            color: #b91c1c;
        }

        .payment-failure-title {
            margin-top: 6px;
            margin-bottom: 6px;
        }

        .payment-failure-text {
            color: #4b5563;
            max-width: 560px;
            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
    <section class="payment-failure-page page-shell-offset">
        <div class="reservation-shell">
            <div class="reservation-form-card payment-failure-card">
              

                <div class="customize-alert customize-alert--error">
                    Payment was cancelled or failed.
                </div>

                <h1 class="payment-failure-title">Payment not completed</h1>
                <p class="payment-failure-text">
                    No charges were finalized. You can try again from your reservation page.
                </p>

                <div style="margin-top: 18px;">
                    <a href="{{ route('reservation.create') }}" class="customize-btn">Try Again</a>
                </div>
            </div>
        </div>
    </section>
@endsection
