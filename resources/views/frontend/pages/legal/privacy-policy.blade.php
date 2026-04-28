@extends('frontend.layouts.app')

@section('content')
    <section class="legal-page">
        <div class="container legal-page__container">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Privacy Policy</span>
            </nav>
            <h1>Privacy Policy</h1>
            <p>
                This Privacy Policy explains how Poema Tours collects, uses, and protects personal data when you visit our
                website, contact us, or submit travel inquiries.
            </p>

            <h2>1. Information We Collect</h2>
            <p>
                We may collect your name, email address, phone number, travel preferences, and other details you voluntarily
                provide through inquiry and subscription forms.
            </p>

            <h2>2. How We Use Information</h2>
            <p>
                Your information is used to respond to requests, prepare travel proposals, manage communications, and send
                relevant promotions where permitted by law.
            </p>

            <h2>3. Data Sharing</h2>
            <p>
                We do not sell personal information. Where necessary, we may share limited data with trusted partners (such as
                hotels or transport providers) solely to support your requested services.
            </p>

            <h2>4. Data Security</h2>
            <p>
                We apply appropriate technical and organizational measures to protect your data against unauthorized access,
                loss, or misuse.
            </p>

            <h2>5. Your Rights</h2>
            <p>
                You may request access, correction, or deletion of your personal data by contacting us. You can also opt out
                of marketing emails at any time using the unsubscribe link.
            </p>

            <h2>6. Contact</h2>
            <p>
                For privacy requests, contact <a href="mailto:hello@poematours.com">hello@poematours.com</a>.
            </p>
        </div>
    </section>
@endsection
