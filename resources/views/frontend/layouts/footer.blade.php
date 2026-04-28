<footer class="site-footer">
    <div class="container site-footer__inner">
        <div class="site-footer__brand">
            <img src="{{ asset('assets/brand/white-logo.svg') }}" alt="Poema Tours white logo" style="height: 120px; object-fit: contain;">
            <p>Curated Egyptian journeys with feeling, meaning, and world-class service.</p>
        </div>

        <div class="site-footer__newsletter">
            <h3>Find Your Next Adventure</h3>
            <form class="site-footer__form" action="#" method="post">
                <input type="text" name="first_name" placeholder="First Name" aria-label="First Name">
                <input type="text" name="last_name" placeholder="Last Name" aria-label="Last Name">
                <input type="email" name="email" placeholder="Email Address" aria-label="Email Address">
                <button type="submit" class="btn btn--primary">Subscribe</button>
            </form>
            <small>
                By entering your email, you agree to our
                <a href="{{ route('terms.of.use') }}">Terms of Use</a>
                and
                <a href="{{ route('privacy.policy') }}">Privacy Policy</a>,
                including receipt of emails and promotions.
            </small>
        </div>

        <div class="site-footer__links">
            <h4>Useful Links</h4>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('about.us') }}">About Us</a>
            <a href="{{ route('our.journeys') }}">Our Journeys</a>
            <a href="{{ route('packages.index') }}">Travel Destinations</a>
            <a href="{{ route('terms.of.use') }}">Terms of Use</a>
            <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
        </div>

        <div class="site-footer__contact">
            <h4>Connect With Us</h4>
            <div class="site-footer__socials">
                <a href="https://www.instagram.com/poematours/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 7.2A4.8 4.8 0 1 0 16.8 12 4.8 4.8 0 0 0 12 7.2zm0 7.9A3.1 3.1 0 1 1 15.1 12 3.1 3.1 0 0 1 12 15.1zM17.8 6.2a1.1 1.1 0 1 0 1.1 1.1 1.1 1.1 0 0 0-1.1-1.1z" fill="currentColor"/>
                        <path d="M17.7 2.9H6.3A3.4 3.4 0 0 0 2.9 6.3v11.4a3.4 3.4 0 0 0 3.4 3.4h11.4a3.4 3.4 0 0 0 3.4-3.4V6.3a3.4 3.4 0 0 0-3.4-3.4zm1.7 14.8a1.7 1.7 0 0 1-1.7 1.7H6.3a1.7 1.7 0 0 1-1.7-1.7V6.3a1.7 1.7 0 0 1 1.7-1.7h11.4a1.7 1.7 0 0 1 1.7 1.7z" fill="currentColor"/>
                    </svg>
                </a>
                <a href="https://x.com/i/flow/login?redirect_after_login=%2F%40poematours" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18.9 3H22l-6.8 7.7L23 21h-6.2l-4.9-6.4L6.2 21H3l7.3-8.3L2 3h6.4l4.4 5.9zM17.9 19h1.7L7.4 4.9H5.6z" fill="currentColor"/>
                    </svg>
                </a>
                <a href="https://support.microsoft.com/en-us/microsoft-365?Type=2&amp;ErrorCode=0x80070057&amp;Culture=en-US" target="_blank" rel="noopener noreferrer" aria-label="Support">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 4h7.5v7.5H4zm8.5 0H20v7.5h-7.5zM4 12.5h7.5V20H4zm8.5 0H20V20h-7.5z" fill="currentColor"/>
                    </svg>
                </a>
                <a href="mailto:hello@poematours.com" aria-label="Email us">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 6h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1zm8 6.2L4.8 8.3h14.4z" fill="currentColor"/>
                    </svg>
                </a>
            </div>
            <a href="mailto:hello@poematours.com">hello@poematours.com</a>
            <span>USA: (915) 504-9504</span>
            <span>EGYPT: 01277339611</span>
        </div>
    </div>

    <div class="container">
        <small class="site-footer__copyright">&copy; {{ date('Y') }} Poema Tours. All rights reserved.</small>
    </div>
</footer>
