@php
    $headerInitialStateClass = ($isHomepage ?? false) ? 'site-header--hero-integrated' : '';
@endphp
<header class="site-header site-header--preload {{ $headerInitialStateClass }}" data-site-header>
    @if (! empty($googleSeoScript ?? null))
        {!! $googleSeoScript !!}
    @endif
    @php
        $headerDestinations = $headerDestinations ?? collect();
        $contactSettings = $contactSettings ?? [];
        $defaultDestination = $headerDestinations->first();
        $defaultDestinationImage =
            $defaultDestination?->imagePublicUrl() ?? asset('assets/images/placeholders/banner.jpeg');
        $featuredDestinations = $headerDestinations->take(2);
        $headerEmail = $contactSettings['email'] ?? 'hello@poematours.com';
        $headerCountry1 = $contactSettings['phone_country_1'] ?? 'USA';
        $headerPhone1 = $contactSettings['phone_number_1'] ?? '+1 915 504 9504';
        $headerCountry2 = $contactSettings['phone_country_2'] ?? 'Egypt';
        $headerPhone2 = $contactSettings['phone_number_2'] ?? '01277339611';
        $headerPhoneHref1 = preg_replace('/[^0-9+]/', '', (string) $headerPhone1);
        $headerPhoneHref2 = preg_replace('/[^0-9+]/', '', (string) $headerPhone2);
        $headerWhatsappNumber = preg_replace('/\D+/', '', (string) ($contactSettings['whatsapp_number'] ?? '201277339611'));
        $headerWhatsappHref = 'https://wa.me/' . $headerWhatsappNumber . '?text=' . rawurlencode('Hello Poema Tours, I would like to inquire about a trip.');
        $customizeTourUrl = route(
            'customize.create',
            request()->filled('package_id') ? ['package_id' => request('package_id')] : []
        );
    @endphp

    <div class="site-header__topbar" data-site-header-topbar>
        <div class="container site-header__topbar-inner">
            <div class="site-header__contact">
                <div class="header-call-dropdown" data-header-call-dropdown>
                    <button
                        type="button"
                        class="header-call-dropdown__toggle"
                        data-header-call-toggle
                        aria-expanded="false"
                        aria-controls="header-call-menu"
                        aria-haspopup="true"
                    >
                        <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M6.8 3.6h2.3c.3 0 .6.2.7.5l1.1 3.1c.1.3 0 .6-.2.8L9.1 9.6a13.4 13.4 0 0 0 5.2 5.2l1.6-1.6c.2-.2.5-.3.8-.2l3.1 1.1c.3.1.5.4.5.7v2.3c0 .4-.3.7-.7.8l-1.5.2c-.6.1-1.2.1-1.8 0A16.7 16.7 0 0 1 5.9 7.2c-.1-.6-.1-1.2 0-1.8l.2-1.5c.1-.4.4-.7.7-.7z"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <span>Call Us</span>
                        <svg class="icon icon--sm header-call-dropdown__chevron" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.7"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div
                        id="header-call-menu"
                        class="header-call-dropdown__menu"
                        role="menu"
                        aria-label="Contact options"
                        aria-hidden="true"
                    >
                        <p class="header-call-dropdown__region">{{ $headerCountry1 }}</p>
                        <a href="tel:{{ $headerPhoneHref1 }}" class="header-call-dropdown__link" role="menuitem">
                            {{ $headerPhone1 }}
                        </a>
                        <p class="header-call-dropdown__region">{{ $headerCountry2 }}</p>
                        <a href="tel:{{ $headerPhoneHref2 }}" class="header-call-dropdown__link" role="menuitem">
                            {{ $headerPhone2 }}
                        </a>
                        <div class="header-call-dropdown__divider" role="separator"></div>
                        <a
                            href="{{ $headerWhatsappHref }}"
                            class="header-call-dropdown__link header-call-dropdown__link--whatsapp"
                            role="menuitem"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <svg class="header-call-dropdown__whatsapp-icon" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                            </svg>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>

                <a href="mailto:{{ $headerEmail }}" class="site-header__email">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 7h16v10H4z" fill="none" stroke="currentColor" stroke-width="1.7"
                            stroke-linejoin="round" />
                        <path d="M4 8l8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.7"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span>{{ $headerEmail }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="container site-header__bar">
        <a href="{{ route('home') }}" class="site-header__logo" aria-label="Poematours home">
            <span class="site-header__wordmark">Poematours</span>
            <img
                class="site-header__logo-img--default"
                src="{{ asset('assets/brand/logo.png') }}"
                alt="Poema Tours logo"
            >
        </a>

        <button class="site-header__mobile-toggle" type="button" aria-expanded="false" aria-controls="mobileNavigation"
            data-mobile-menu-toggle>
            <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M4 7h16M4 12h16M4 17h16" fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" />
            </svg>
            <span class="sr-only">Open menu</span>
        </button>

        <nav class="site-nav" aria-label="Main navigation">
            <a href="{{ route('about.us') }}" class="site-nav__link">About Us</a>
            <button type="button" class="site-nav__link site-nav__link--button" aria-expanded="false"
                aria-controls="destinationMegaMenu" data-destination-toggle>
                Destinations
            </button>
            <a href="{{ route('activities.show', 'culture') }}" class="site-nav__link">Activities</a>
            <a href="{{ route('our.journeys') }}" class="site-nav__link">Our Journeys</a>
            <a href="{{ $customizeTourUrl }}" class="site-nav__link site-nav__link--hero-compact">Customize Tour</a>
            <a href="{{ route('reservation.create') }}" class="site-nav__link site-nav__link--hero-compact">Reservation</a>
        </nav>

        <div class="site-header__actions">

            <a href="{{ route('search') }}" class="site-header__find-journey">
                <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" fill="none" stroke="currentColor"
                        stroke-width="1.8" stroke-linecap="round" />
                    <path d="M16.5 16.5L21 21" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" />
                </svg>
                <span>Find your journey</span>
            </a>
        </div>
    </div>

    <div class="mega-menu" id="destinationMegaMenu" aria-hidden="true" data-destination-menu>
        <div class="mega-menu__shell container">
            <div class="mega-menu__column mega-menu__column--left">
                <button type="button" class="mega-menu__close" aria-label="Close destinations menu"
                    data-destination-close>
                    <svg class="icon icon--md" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" />
                    </svg>
                </button>
                <ul class="mega-menu__categories mega-menu__left-top" data-category-list>
                    <li>
                        <button type="button" class="mega-menu__category is-active" data-preview-label="Egypt"
                            data-preview-src="{{ asset('assets/images/placeholders/banner.jpeg') }}">
                            Destinations
                        </button>
                    </li>
                    <li><a href="{{ route('our.journeys') }}" class="mega-menu__category">Our journeys</a></li>
                </ul>

                <p class="mega-menu__popular-label">Popular</p>
                <ul class="mega-menu__popular-links">
                    <li><a href="{{ route('packages.index', ['trip_type' => 'small-group']) }}">Small Group
                            Journeys</a></li>

                    <li><a href="{{ route('packages.index', ['trip_type' => 'private']) }}">Private Ready-To-Book
                            Journeys</a></li>
                </ul>

                <div class="mega-menu__left-divider"></div>
                <ul class="mega-menu__secondary-links">
                    <li><a href="{{ route('our.journeys') }}" class="mega-menu__link-row">Stories</a></li>
                    <li><a href="{{ route('search') }}" class="mega-menu__link-row">Journey Finder</a></li>

                </ul>
            </div>

            <div class="mega-menu__column mega-menu__column--middle">
                <div class="mega-menu__featured-grid">
                    @foreach ($featuredDestinations as $destination)
                        <a href="{{ route('packages.index', ['destination' => $destination->slug]) }}"
                            class="featured-card">
                            <img src="{{ $destination->imagePublicUrl() ?? asset('assets/images/placeholders/banner.jpeg') }}"
                                alt="{{ $destination->name }} destination">
                            <span>{{ $destination->name }}</span>
                        </a>
                    @endforeach
                </div>

                <a href="{{ route('destinations.index') }}" class="mega-menu__all-destinations">All Destinations</a>

                <div class="mega-menu__regions" role="navigation" aria-label="Destination regions">
                    @foreach ($headerDestinations as $destination)
                        <a href="{{ route('packages.index', ['destination' => $destination->slug]) }}"
                            class="mega-menu__region-row">
                            {{ $destination->name }}
                            <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M9 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.9"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mega-menu__column mega-menu__column--preview" data-preview-panel>
                <img src="{{ $defaultDestinationImage }}" alt="{{ $defaultDestination?->name ?? 'Egypt' }} preview"
                    data-preview-target-image>
                <div class="mega-menu__preview-overlay"></div>
                <h3 class="mega-menu__preview-title" data-preview-target-title>
                    {{ $defaultDestination?->name ?? 'Egypt' }}</h3>
            </div>
        </div>
    </div>

</header>
