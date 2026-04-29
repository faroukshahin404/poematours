@extends('frontend.layouts.app')

@section('content')
    <section class="auth-page">
        <div class="container auth-page__container">
            <div class="auth-card">
                <div class="auth-card__header">
                    <p class="auth-card__eyebrow">Welcome Back</p>
                    <h1>Login to your account</h1>
                    <p>Access your bookings, saved journeys, and travel updates.</p>
                </div>

                <form class="auth-form" action="#" method="post">
                    @csrf
                    <label for="loginEmail">Email Address</label>
                    <input id="loginEmail" type="email" name="email" placeholder="name@example.com" required>

                    <label for="loginPassword">Password</label>
                    <input id="loginPassword" type="password" name="password" placeholder="Enter your password" required>

                    <div class="auth-form__actions">
                        <button type="submit" class="btn btn-primary auth-form__submit">Login</button>
                        <a href="{{ route('password.request') }}" class="auth-form__link">Forgot password?</a>
                    </div>
                </form>

                <p class="auth-card__footer">
                    New to Poema Tours?
                    <a href="{{ route('register') }}">Create an account</a>
                </p>
            </div>
        </div>
    </section>
@endsection
