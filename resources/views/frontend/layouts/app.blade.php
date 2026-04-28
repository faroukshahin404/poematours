<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poema Tours | Enter Egypt</title>
    <link rel="stylesheet" href="{{ asset('assets/css/frontend.css') }}">
    @stack('styles')
</head>
<body>
    @include('frontend.layouts.header')
    @yield('content')
    @include('frontend.layouts.footer')

    <div class="chat-widget" data-chat-widget>
        <div class="chat-widget__panel" data-chat-panel aria-hidden="true">
            <div class="chat-widget__panel-head">
                <h3>Chat With Poema Tours</h3>
                <p>Share your details and our team will contact you shortly.</p>
            </div>
            <form class="chat-widget__form" action="#" method="post">
                <div class="chat-widget__grid">
                    <input type="text" name="first_name" placeholder="First Name" aria-label="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" aria-label="Last Name" required>
                </div>
                <div class="chat-widget__phone">
                    <select name="country_prefix" aria-label="Country prefix" required>
                        <option value="+1">+1 (USA)</option>
                        <option value="+20">+20 (Egypt)</option>
                        <option value="+44">+44 (UK)</option>
                        <option value="+971">+971 (UAE)</option>
                    </select>
                    <input type="tel" name="phone_number" placeholder="Phone Number" aria-label="Phone Number" required>
                </div>
                <input type="email" name="email" placeholder="Email Address" aria-label="Email Address" required>
                <textarea name="notes" rows="4" placeholder="Notes" aria-label="Notes"></textarea>
                <button type="submit" class="btn btn--primary">Send</button>
            </form>
        </div>

        <button type="button" class="chat-widget__toggle" data-chat-toggle aria-expanded="false" aria-label="Open chat">
            <svg class="chat-widget__icon chat-widget__icon--open" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 3a8 8 0 0 0-8 8c0 2.2.9 4.2 2.4 5.6V21l4-2.2c.5.1 1 .2 1.6.2a8 8 0 1 0 0-16z" fill="currentColor"/>
                <circle cx="8.5" cy="11.4" r="1" fill="#fff"/>
                <circle cx="12" cy="11.4" r="1" fill="#fff"/>
                <circle cx="15.5" cy="11.4" r="1" fill="#fff"/>
            </svg>
            <svg class="chat-widget__icon chat-widget__icon--close" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round"/>
            </svg>
        </button>
    </div>

    <script src="{{ asset('assets/js/frontend.js') }}"></script>
    @stack('scripts')
</body>
</html>
