@extends('frontend.layouts.app')

@push('styles')
    <style>
        .payment-success-page {
            padding-top: 132px;
            padding-bottom: 72px;
            background: var(--color-009);
            min-height: calc(100vh - 120px);
        }

        @media (max-width: 768px) {
            .payment-success-page {
                padding-top: 108px;
            }
        }

        .payment-success-card {
            text-align: center;
        }

        .payment-success-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #dcfce7;
            color: #15803d;
        }

        .payment-success-title {
            margin-top: 6px;
            margin-bottom: 6px;
        }

        .payment-success-text {
            color: #4b5563;
            max-width: 560px;
            margin: 0 auto;
        }

        .payment-success-meta {
            margin-top: 12px;
            color: #6b7280;
            word-break: break-word;
        }
    </style>
@endpush

@section('content')
    <section class="payment-success-page">
        <div class="reservation-shell">
            <div class="reservation-form-card payment-success-card">
                

                <div class="customize-alert customize-alert--success">
                    Payment completed successfully.
                </div>

                <h1 class="payment-success-title">Thank you for your payment</h1>
                <p class="payment-success-text">
                    Your transaction is being confirmed. Our team will contact you soon with your booking details.
                </p>

                @if ($sessionId !== '')
                    <p class="payment-success-meta">
                        Stripe Session ID: <strong>{{ $sessionId }}</strong>
                    </p>
                @endif

                <div style="margin-top: 18px;">
                    <a href="{{ route('home') }}" class="customize-btn">Back to Home</a>
                </div>
            </div>
        </div>
    </section>
@endsection
