<header class="site-header" data-site-header>
    <div class="container site-header__bar">
        <a href="{{ route('home') }}" class="site-header__logo" aria-label="Poema Tours home">
            <img src="{{ asset('assets/brand/logo.png') }}" alt="Poema Tours logo">
        </a>

        <button
            class="site-header__mobile-toggle"
            type="button"
            aria-expanded="false"
            aria-controls="mobileNavigation"
            data-mobile-menu-toggle
        >
            <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <span class="sr-only">Open menu</span>
        </button>

        <nav class="site-nav" aria-label="Main navigation">
            <a href="{{ route('about.us') }}" class="site-nav__link">About Us</a>
            <button
                type="button"
                class="site-nav__link site-nav__link--button"
                aria-expanded="false"
                aria-controls="destinationMegaMenu"
                data-destination-toggle
            >
                Destinations
            </button>
            <a href="{{ route('our.journeys') }}" class="site-nav__link">Our Journeys</a>
        </nav>

        <div class="site-header__actions">
            <a href="#" class="auth-btn auth-btn--ghost" aria-label="Login">
                <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4zm0 2c-3.2 0-6.2 1.7-7.5 4.4-.2.4.1.8.6.8h13.8c.5 0 .8-.4.6-.8-1.3-2.7-4.3-4.4-7.5-4.4z" fill="currentColor"/>
                </svg>
                <span>Login</span>
            </a>
            <a href="#" class="auth-btn auth-btn--solid" aria-label="Register">
                <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Register</span>
            </a>
        </div>
    </div>

    <div class="mega-menu" id="destinationMegaMenu" aria-hidden="true" data-destination-menu>
        <div class="mega-menu__shell container">
            <div class="mega-menu__column mega-menu__column--left">
                <button type="button" class="mega-menu__close" aria-label="Close destinations menu" data-destination-close>
                    <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <ul class="mega-menu__categories" data-category-list>
                    <li><button type="button" class="mega-menu__category is-active" data-preview-label="Egypt" data-preview-src="{{ asset('assets/images/placeholders/banner.jpeg') }}">Destinations</button></li>
                    <li><button type="button" class="mega-menu__category" data-preview-label="Nile Journey" data-preview-src="{{ asset('assets/images/placeholders/banner.jpeg') }}">Our Journeys</button></li>
                    <li><button type="button" class="mega-menu__category" data-preview-label="A&amp;K Sanctuary" data-preview-src="{{ asset('assets/images/placeholders/banner.jpeg') }}">A&amp;K Sanctuary</button></li>
                </ul>

                <p class="mega-menu__popular-label">Popular</p>
                <ul class="mega-menu__popular-links">
                    <li><a href="#">Luxury Nile Escape</a></li>
                    <li><a href="#">Timeless Cairo</a></li>
                    <li><a href="#">Pharaohs Discovery</a></li>
                    <li><a href="#">Red Sea Serenity</a></li>
                </ul>
            </div>

            <div class="mega-menu__column mega-menu__column--middle">
                <div class="mega-menu__featured-grid">
                    <a href="#" class="featured-card">
                        <img src="{{ asset('assets/images/placeholders/banner.jpeg') }}" alt="Luxor destination">
                        <span>Luxor</span>
                    </a>
                    <a href="#" class="featured-card">
                        <img src="{{ asset('assets/images/placeholders/banner.jpeg') }}" alt="Cairo destination">
                        <span>Cairo</span>
                    </a>
                </div>

                <a href="#" class="mega-menu__all-destinations">All Destinations</a>

                <div class="mega-menu__regions" role="navigation" aria-label="Destination regions">
                    <a href="#" class="mega-menu__region-row">Africa <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="#" class="mega-menu__region-row">Antarctica &amp; The Arctic <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="#" class="mega-menu__region-row">Asia <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="#" class="mega-menu__region-row">Australasia <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="#" class="mega-menu__region-row">Central America <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="#" class="mega-menu__region-row">Europe <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="#" class="mega-menu__region-row">Middle East <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="#" class="mega-menu__region-row">South America <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                </div>
            </div>

            <div class="mega-menu__column mega-menu__column--preview" data-preview-panel>
                <img src="{{ asset('assets/images/placeholders/banner.jpeg') }}" alt="Egypt preview" data-preview-target-image>
                <div class="mega-menu__preview-overlay"></div>
                <h3 class="mega-menu__preview-title" data-preview-target-title>Egypt</h3>
            </div>
        </div>
    </div>

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
            <button type="button" data-mobile-destination-toggle>Destinations</button>
            <a href="{{ route('our.journeys') }}">Our Journeys</a>
            <a href="#">Login</a>
            <a href="#">Register</a>
        </nav>
    </div>
</header>
