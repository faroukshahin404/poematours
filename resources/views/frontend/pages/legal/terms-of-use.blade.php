@extends('frontend.layouts.app')

@section('content')
    <section class="legal-page">
        <div class="container legal-page__container">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Terms of Use</span>
            </nav>
            <h1>Terms of Use</h1>
            <p>
                Welcome to Poema Tours. By accessing or using our website, you agree to the terms below. These terms govern
                your use of our content, services, and booking-related requests.
            </p>

            <h2>1. Website Use</h2>
            <p>
                You may browse and use this website for personal, non-commercial travel planning. You agree not to misuse the
                site, interfere with normal operation, or attempt unauthorized access to systems or data.
            </p>

            <h2>2. Content and Accuracy</h2>
            <p>
                We aim to keep all information accurate and current, including itineraries, prices, and availability. However,
                details may change without prior notice and final confirmations are always provided during direct consultation.
            </p>

            <h2>3. Bookings and Payments</h2>
            <p>
                Any booking request submitted through Poema Tours is subject to confirmation. Final pricing, cancellation
                policies, payment schedules, and inclusions are provided in writing before payment is completed.
            </p>

            <h2>4. Intellectual Property</h2>
            <p>
                All website materials, including text, visuals, branding, and design assets, are owned by Poema Tours or used
                with permission. You may not copy, reproduce, or distribute this content without written approval.
            </p>

            <h2>5. Limitation of Liability</h2>
            <p>
                Poema Tours is not liable for indirect or consequential loss related to website usage, temporary service
                interruptions, or third-party external links.
            </p>

            <h2>6. Contact</h2>
            <p>
                For terms inquiries, contact us at <a href="mailto:hello@poematours.com">hello@poematours.com</a>.
            </p>
        </div>
    </section>
@endsection
