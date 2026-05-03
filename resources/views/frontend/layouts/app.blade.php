<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poema Tours | Enter Egypt</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/brand/favicon.svg') }}">
    @php
        $fontMcQueenRegular = public_path('assets/fonts/McQueen/McQueen Grotesk/Fonts WEB/McQueenGroteskTrial-Regular.woff2');
        $fontArgentRegular = public_path('assets/fonts/Argent-CF/Demo_Fonts/Fontspring-DEMO-argentcf-regular.otf');
        $fontMcQueenPreloadUrl = asset(
            'assets/fonts/McQueen/' . rawurlencode('McQueen Grotesk') . '/' . rawurlencode('Fonts WEB') . '/McQueenGroteskTrial-Regular.woff2'
        );
    @endphp
    @if (file_exists($fontMcQueenRegular))
        <link rel="preload" href="{{ $fontMcQueenPreloadUrl }}" as="font" type="font/woff2" crossorigin>
    @endif
    @if (file_exists($fontArgentRegular))
        <link rel="preload" href="{{ asset('assets/fonts/Argent-CF/Demo_Fonts/Fontspring-DEMO-argentcf-regular.otf') }}" as="font" type="font/otf" crossorigin>
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/theme-variables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/frontend.css') }}">
    @stack('styles')
</head>
<body class="{{ request()->routeIs('home') ? 'is-homepage' : 'is-inner-page' }}">
    @include('frontend.layouts.header')
    <button
        type="button"
        class="mobile-drawer__backdrop"
        aria-hidden="true"
        tabindex="-1"
        style="display: none;"
        data-mobile-menu-backdrop
    ></button>

    <div class="mobile-drawer" id="mobileNavigation" aria-hidden="true" data-mobile-menu>
        <div class="mobile-drawer__header">
            <button type="button" class="mobile-drawer__close" data-mobile-menu-close aria-label="Close menu">
                <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
        <nav class="mobile-drawer__links" aria-label="Mobile navigation">
            <a href="{{ route('about.us') }}">About Us</a>
            <button
                type="button"
                aria-expanded="false"
                aria-controls="mobileDestinationPanel"
                data-mobile-destination-toggle
            >
                Destinations in Egypt
            </button>
            @php
                $mobileDrawerDestinations = [
                    ['label' => 'Cairo', 'image' => 'assets/images/placeholders/banner.jpeg'],
                    ['label' => 'Luxor', 'image' => 'assets/images/placeholders/nile-3.jpg'],
                    ['label' => 'Aswan', 'image' => 'assets/images/placeholders/sea-1.jpg'],
                    ['label' => 'Alexandria', 'image' => 'assets/images/placeholders/sea-2.jpg'],
                    ['label' => 'Sharm El Sheikh', 'image' => 'assets/images/placeholders/hotel-2.jpg'],
                    ['label' => 'Hurghada', 'image' => 'assets/images/placeholders/sea-5.jpg'],
                    ['label' => 'Siwa Oasis', 'image' => 'assets/images/placeholders/template-1.jpeg'],
                ];
            @endphp
            <div
                class="mobile-drawer__destination-panel"
                id="mobileDestinationPanel"
                data-mobile-destination-list
                aria-hidden="true"
            >
                <div class="mobile-drawer__destination-rail" role="list" aria-label="Egypt destinations">
                    @foreach ($mobileDrawerDestinations as $destination)
                        <a
                            href="{{ route('packages.index') }}"
                            class="mobile-drawer__destination-card"
                            role="listitem"
                        >
                            <img
                                src="{{ asset($destination['image']) }}"
                                alt="{{ $destination['label'] }}"
                                loading="lazy"
                                decoding="async"
                            >
                            <span class="mobile-drawer__destination-label">{{ $destination['label'] }}</span>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('destinations.index') }}" class="mobile-drawer__all-destinations-btn">
                    All Destinations
                </a>
            </div>
            <a href="{{ route('activities.show', 'culture') }}">Activities</a>
            <a href="{{ route('our.journeys') }}">Our Journeys</a>
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </nav>
    </div>

    <main class="site-main">
        @yield('content')
    </main>
    @include('frontend.layouts.footer')

    @unless (request()->routeIs('packages.show'))
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
    @endunless

    <script src="{{ asset('assets/js/frontend.js') }}"></script>
    @stack('scripts')
</body>
</html>
