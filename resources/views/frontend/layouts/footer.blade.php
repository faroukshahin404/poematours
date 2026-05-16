<footer class="site-footer" role="contentinfo">
    @php
        $contactSettings = $contactSettings ?? [];
        $footerEmail = $contactSettings['email'] ?? 'hello@poematours.com';
        $footerSocialEmail = $contactSettings['social_email'] ?? $footerEmail;
        $footerCountry1 = $contactSettings['phone_country_1'] ?? 'USA';
        $footerPhone1 = $contactSettings['phone_number_1'] ?? '(915) 504-9504';
        $footerCountry2 = $contactSettings['phone_country_2'] ?? 'Egypt';
        $footerPhone2 = $contactSettings['phone_number_2'] ?? '01277339611';
        $footerFacebook = $contactSettings['facebook_url'] ?? null;
        $footerInstagram = $contactSettings['instagram_url'] ?? 'https://www.instagram.com/poematours/';
        $footerTripadvisor = $contactSettings['tripadvisor_url'] ?? null;
        $footerTiktok = $contactSettings['tiktok_url'] ?? null;
        $footerLinkedin = $contactSettings['linkedin_url'] ?? null;
        $footerX = $contactSettings['x_url'] ?? 'https://x.com/i/flow/login?redirect_after_login=%2F%40poematours';
    @endphp
    <div class="container">
        <div class="site-footer__top">
            <div class="site-footer__brand">
                <a href="{{ route('home') }}" class="site-footer__brand-name" aria-label="Poema Tours home">Poema Tours</a>
                <p>Curated Egyptian journeys with feeling, meaning, and world-class service.</p>
                <a href="{{ route('our.journeys') }}" class="site-footer__brand-cta">Explore our journeys</a>
            </div>

            <div class="site-footer__newsletter">
                <h3>Get Travel Inspiration First</h3>
                <p>Join our list for seasonal offers, new routes, and practical Egypt travel tips.</p>
                <form class="site-footer__form" action="{{ route('contact-leads.newsletter.store') }}" method="post">
                    @csrf
                    <input type="text" name="first_name" placeholder="First Name" aria-label="First Name">
                    <input type="text" name="last_name" placeholder="Last Name" aria-label="Last Name">
                    <input type="email" name="email" placeholder="Email Address" aria-label="Email Address">
                    <button type="submit" class="btn btn--primary">Subscribe</button>
                </form>
                <small>
                    By entering your email, you agree to our
                    <a href="{{ route('terms.of.use') }}">Terms and Conditions</a>,
                    <a href="{{ route('privacy.policy') }}">Privacy Policy</a>@foreach ($footerLegalPages ?? [] as $footerPage),
                    <a href="{{ route('pages.show', $footerPage) }}">{{ $footerPage->footer_label ?: $footerPage->name }}</a>@endforeach,
                    including receipt of emails and promotions.
                </small>
            </div>
        </div>

        <div class="site-footer__main">
            <nav class="site-footer__links" aria-label="Footer navigation">
                <h4>Explore</h4>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about.us') }}">About Us</a>
                <a href="{{ route('our.journeys') }}">Our Journeys</a>
                <a href="{{ route('packages.index') }}">Travel Destinations</a>
            </nav>

            <nav class="site-footer__links" aria-label="Legal links">
                <h4>Legal</h4>
                <a href="{{ route('terms.of.use') }}">Terms of Use</a>
                <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
                @foreach ($footerLegalPages ?? [] as $footerPage)
                    <a href="{{ route('pages.show', $footerPage) }}">{{ $footerPage->footer_label ?: $footerPage->name }}</a>
                @endforeach
            </nav>

            <div class="site-footer__contact">
                <h4>Contact</h4>
                <a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a>
                <span>{{ $footerCountry1 }}: {{ $footerPhone1 }}</span>
                <span>{{ $footerCountry2 }}: {{ $footerPhone2 }}</span>
                <div class="site-footer__socials" aria-label="Social links">
                    @if ($footerFacebook)
                        <a href="{{ $footerFacebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M13.5 8H16V5h-2.5C10.5 5 9 6.8 9 9.5V12H7v3h2v5h3v-5h2.4l.6-3H12V9.8c0-1 .4-1.8 1.5-1.8z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if ($footerInstagram)
                        <a href="{{ $footerInstagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 7.2A4.8 4.8 0 1 0 16.8 12 4.8 4.8 0 0 0 12 7.2zm0 7.9A3.1 3.1 0 1 1 15.1 12 3.1 3.1 0 0 1 12 15.1z" fill="currentColor"/>
                                <path d="M17.7 2.9H6.3A3.4 3.4 0 0 0 2.9 6.3v11.4a3.4 3.4 0 0 0 3.4 3.4h11.4a3.4 3.4 0 0 0 3.4-3.4V6.3a3.4 3.4 0 0 0-3.4-3.4zm1.7 14.8a1.7 1.7 0 0 1-1.7 1.7H6.3a1.7 1.7 0 0 1-1.7-1.7V6.3a1.7 1.7 0 0 1 1.7-1.7h11.4a1.7 1.7 0 0 1 1.7 1.7z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if ($footerTripadvisor)
                        <a href="{{ $footerTripadvisor }}" target="_blank" rel="noopener noreferrer" aria-label="Tripadvisor">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 9.8a2.2 2.2 0 1 0 2.2 2.2A2.2 2.2 0 0 0 12 9.8zm0 3.2a1 1 0 1 1 1-1 1 1 0 0 1-1 1z" fill="currentColor"/>
                                <path d="M22 9.2h-2.3a7.7 7.7 0 0 0-15.4 0H2v1.6h1a3.4 3.4 0 1 0 3.4 3.4 3.3 3.3 0 0 0-.3-1.5h3.7a3.4 3.4 0 1 0 4.4 0H18a3.4 3.4 0 1 0 4-3.5zM5.3 16a1.8 1.8 0 1 1 1.8-1.8A1.8 1.8 0 0 1 5.3 16zm13.4 0a1.8 1.8 0 1 1 1.8-1.8 1.8 1.8 0 0 1-1.8 1.8z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if ($footerTiktok)
                        <a href="{{ $footerTiktok }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M16.8 3c.5 1.5 1.6 2.6 3.2 3v3a8.1 8.1 0 0 1-3.2-.9v6.1a5.2 5.2 0 1 1-5.2-5.2h.4v3a2.2 2.2 0 1 0 1.8 2.2V3z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if ($footerLinkedin)
                        <a href="{{ $footerLinkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M6 9H3v12h3zM4.5 3A1.8 1.8 0 1 0 6.3 4.8 1.8 1.8 0 0 0 4.5 3zM21 14.2c0-3.1-1.7-5.2-4.5-5.2a3.9 3.9 0 0 0-3.5 1.9V9H10v12h3v-6.7c0-1.7.9-2.7 2.3-2.7s2.1 1 2.1 2.7V21h3z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if ($footerX)
                        <a href="{{ $footerX }}" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18.9 3H22l-6.8 7.7L23 21h-6.2l-4.9-6.4L6.2 21H3l7.3-8.3L2 3h6.4l4.4 5.9zM17.9 19h1.7L7.4 4.9H5.6z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                    @if ($footerSocialEmail)
                        <a href="mailto:{{ $footerSocialEmail }}" aria-label="Email us">
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M4 6h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1zm8 6.2L4.8 8.3h14.4z" fill="currentColor"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="site-footer__bottom">
            <small class="site-footer__copyright">&copy; {{ date('Y') }} Poema Tours. All rights reserved.</small>
            <a href="{{ route('home') }}" class="site-footer__backtop" aria-label="Back to top">Back to top</a>
        </div>
    </div>
</footer>
