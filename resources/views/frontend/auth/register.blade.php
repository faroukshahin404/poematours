@extends('frontend.layouts.app')

@section('content')
    <section class="auth-page">
        <div class="container auth-page__container">
            <div class="auth-card">
                <div class="auth-card__header">
                    <p class="auth-card__eyebrow">Join Poema Tours</p>
                    <h1>Create your account</h1>
                    <p>Register once to manage your travel preferences and receive tailored offers.</p>
                </div>

                <form class="auth-form" action="#" method="post">
                    @csrf

                    <div class="auth-form__grid">
                        <div>
                            <label for="registerFirstName">First Name</label>
                            <input id="registerFirstName" type="text" name="first_name" placeholder="First name" required>
                        </div>

                        <div>
                            <label for="registerLastName">Last Name</label>
                            <input id="registerLastName" type="text" name="last_name" placeholder="Last name" required>
                        </div>
                    </div>

                    <label for="registerEmail">Email Address</label>
                    <input id="registerEmail" type="email" name="email" placeholder="name@example.com" required>

                    <label for="registerPassword">Password</label>
                    <input id="registerPassword" type="password" name="password" placeholder="Create a password" required>

                    <fieldset class="auth-form__preferences">
                        <legend>Please let us know how you'd like us to keep you updated with our latest news and offers:</legend>

                        <label class="auth-form__check">
                            <input type="checkbox" name="preferences[]" value="email">
                            <span>Email</span>
                        </label>

                        <label class="auth-form__check">
                            <input type="checkbox" name="preferences[]" value="post">
                            <span>Post</span>
                        </label>

                        <label class="auth-form__check">
                            <input type="checkbox" name="preferences[]" value="phone">
                            <span>Phone</span>
                        </label>

                        <label class="auth-form__check">
                            <input type="checkbox" name="preferences[]" value="text_message">
                            <span>Text message</span>
                        </label>
                    </fieldset>

                    <div class="auth-form__actions">
                        <button type="submit" class="btn btn-primary auth-form__submit">Register</button>
                    </div>
                </form>

                <p class="auth-card__footer">
                    Already have an account?
                    <a href="{{ route('login') }}">Login</a>
                </p>
            </div>
        </div>
    </section>
@endsection
