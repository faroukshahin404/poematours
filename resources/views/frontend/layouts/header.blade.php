<header class="site-header site-header--preload" data-site-header>
    <div class="site-header__topbar">
        <div class="container site-header__topbar-inner">
            <div class="site-header__contact">
                <div class="header-call-dropdown">
                    <button type="button" class="header-call-dropdown__toggle" aria-expanded="false">
                        <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M6.8 3.6h2.3c.3 0 .6.2.7.5l1.1 3.1c.1.3 0 .6-.2.8L9.1 9.6a13.4 13.4 0 0 0 5.2 5.2l1.6-1.6c.2-.2.5-.3.8-.2l3.1 1.1c.3.1.5.4.5.7v2.3c0 .4-.3.7-.7.8l-1.5.2c-.6.1-1.2.1-1.8 0A16.7 16.7 0 0 1 5.9 7.2c-.1-.6-.1-1.2 0-1.8l.2-1.5c.1-.4.4-.7.7-.7z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>Call Us</span>
                        <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="header-call-dropdown__menu" aria-label="Phone numbers">
                        <p>USA</p>
                        <a href="tel:+19155049504">+1 915 504 9504</a>
                        <p>Egypt</p>
                        <a href="tel:+201277339611">01277339611</a>
                    </div>
                </div>

                <a href="mailto:hello@poematours.com" class="site-header__email">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 7h16v10H4z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                        <path d="M4 8l8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>hello@poematours.com</span>
                </a>
            </div>
        </div>
    </div>

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
            <a href="{{ route('activities.show', 'culture') }}" class="site-nav__link">Activities</a>
            <a href="{{ route('our.journeys') }}" class="site-nav__link">Our Journeys</a>
        </nav>

        <div class="site-header__actions">
            <a href="{{ route('login') }}" class="auth-btn auth-btn--ghost" aria-label="Login">
                <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 11a3 3 0 1 0-3-3 3 3 0 0 0 3 3z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3.8 18.2c.9-2.1 2.8-3.4 5.2-3.4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M13 9.5h6.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M16.7 6.3L20 9.5l-3.3 3.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Login</span>
            </a>
            <a href="{{ route('register') }}" class="auth-btn auth-btn--solid" aria-label="Register">
                <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M9 10.8a3 3 0 1 0-3-3 3 3 0 0 0 3 3z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3.8 18.2c1-2.3 3.1-3.7 5.7-3.7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M16.5 8v7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M13 11.5h7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
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
                <ul class="mega-menu__categories mega-menu__left-top" data-category-list>
                    <li>
                        <button type="button" class="mega-menu__category is-active" data-preview-label="Egypt" data-preview-src="{{ asset('assets/images/placeholders/banner.jpeg') }}">
                            Destinations
                        </button>
                    </li>
                    <li><a href="{{ route('our.journeys') }}" class="mega-menu__category">Our journeys</a></li>
                    <li><a href="{{ route('packages.index') }}" class="mega-menu__category">A&amp;K Sanctuary</a></li>
                </ul>

                <p class="mega-menu__popular-label">Popular</p>
                <ul class="mega-menu__popular-links">
                    <li><a href="{{ route('packages.index') }}">Small Group Journeys</a></li>
                    <li><a href="{{ route('packages.index') }}">Expedition Cruises</a></li>
                    <li><a href="{{ route('packages.index') }}">Private Ready-To-Book Journeys</a></li>
                    <li><a href="{{ route('packages.index') }}">A&amp;K Crystal Cruises</a></li>
                    <li><a href="{{ route('packages.index') }}">A&amp;K Private Jet Journeys</a></li>
                </ul>

                <div class="mega-menu__left-divider"></div>
                <ul class="mega-menu__secondary-links">
                    <li><a href="{{ route('packages.index') }}" class="mega-menu__link-row">The Exclusive Collection</a></li>
                    <li><a href="{{ route('our.journeys') }}" class="mega-menu__link-row">Travel Ideas</a></li>
                    <li><a href="{{ route('our.journeys') }}" class="mega-menu__link-row">Stories</a></li>
                    <li><a href="{{ route('packages.index') }}" class="mega-menu__link-row">Journey Finder</a></li>
                    <li><a href="{{ route('packages.index') }}" class="mega-menu__link-row">Offers</a></li>
                    <li><a href="{{ route('about.us') }}" class="mega-menu__link-row">About Us</a></li>
                    <li><a href="{{ route('packages.index') }}" class="mega-menu__link-row">A&amp;K Philanthropy</a></li>
                </ul>
            </div>

            <div class="mega-menu__column mega-menu__column--middle">
                <div class="mega-menu__featured-grid">
                    <a href="{{ route('packages.index') }}" class="featured-card">
                        <img src="{{ asset('assets/images/placeholders/banner.jpeg') }}" alt="Cairo destination">
                        <span>Cairo</span>
                    </a>
                    <a href="{{ route('packages.index') }}" class="featured-card">
                        <img src="{{ asset('assets/images/placeholders/sea-1.jpg') }}" alt="Aswan destination">
                        <span>Aswan</span>
                    </a>
                </div>

                <a href="{{ route('packages.index') }}" class="mega-menu__all-destinations">All Destinations</a>

                <div class="mega-menu__regions" role="navigation" aria-label="Destination regions">
                    <a href="{{ route('packages.index') }}" class="mega-menu__region-row">Cairo <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="{{ route('packages.index') }}" class="mega-menu__region-row">Aswan <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="{{ route('packages.index') }}" class="mega-menu__region-row">Luxor <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="{{ route('packages.index') }}" class="mega-menu__region-row">Giza <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="{{ route('packages.index') }}" class="mega-menu__region-row">Sharm El Sheikh <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="{{ route('packages.index') }}" class="mega-menu__region-row">Newiba <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a href="{{ route('packages.index') }}" class="mega-menu__region-row">Dahab<svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                </div>
            </div>

            <div class="mega-menu__column mega-menu__column--preview" data-preview-panel>
                <img src="{{ asset('assets/images/placeholders/banner.jpeg') }}" alt="Egypt preview" data-preview-target-image>
                <div class="mega-menu__preview-overlay"></div>
                <h3 class="mega-menu__preview-title" data-preview-target-title>Egypt</h3>
            </div>
        </div>
    </div>

</header>
