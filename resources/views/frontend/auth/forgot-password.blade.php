@extends('frontend.layouts.app')

@section('content')
    <section class="auth-page">
        <div class="container auth-page__container">
            <div class="auth-card auth-card--compact">
                <div class="auth-card__header">
                    <p class="auth-card__eyebrow">Password Assistance</p>
                    <h1>Forgot your password?</h1>
                    <p>Enter your email address and we will send you instructions to set a new password.</p>
                </div>

                <form class="auth-form" action="#" method="post">
                    @csrf
                    <label for="forgotEmail">Email Address</label>
                    <input id="forgotEmail" type="email" name="email" placeholder="name@example.com" required>

                    <div class="auth-form__actions">
                        <button type="submit" class="btn btn-primary auth-form__submit">Send reset link</button>
                    </div>
                </form>

                <p class="auth-card__footer">
                    Remembered your password?
                    <a href="{{ route('login') }}">Back to login</a>
                </p>
            </div>
        </div>
    </section>
@endsection
