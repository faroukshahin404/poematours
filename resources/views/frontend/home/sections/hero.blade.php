<section class="hero">
    <img class="hero__background" src="{{ asset('assets/images/placeholders/banner.jpeg') }}" alt="Golden sunrise over Egypt landscapes">
    <div class="hero__overlay"></div>

    <div class="container hero__content">
        <p class="hero__eyebrow">Enter Egypt</p>
        <h1 class="hero__title">
            Where Every Step Whispers History
        </h1>
        <p class="hero__subtitle">
            Not just a destination. A feeling of wonder, warmth, and timeless beauty waiting to meet you.
        </p>
        <form class="hero-search" action="{{ route('packages.index') }}" method="GET">
            <div class="hero-search__field">
                <label for="destination">Destination</label>
                <select id="destination" name="destination">
                    <option value="">Select destination</option>
                    <option value="cairo">Cairo</option>
                    <option value="aswan">Aswan</option>
                    <option value="luxor">Luxor</option>
                </select>
            </div>

            <div class="hero-search__field hero-search__field--date">
                <label for="travel_date">Travel Date</label>
                <input
                    id="travel_date"
                    name="travel_date"
                    type="text"
                    aria-label="Travel date range"
                    placeholder="Select travel date range"
                    autocomplete="off"
                >
            </div>

            <div class="hero-search__field">
                <label for="adults">Adults</label>
                <select id="adults" name="adults">
                    <option value="1">1 Adult</option>
                    <option value="2" selected>2 Adults</option>
                    <option value="3">3 Adults</option>
                    <option value="4">4 Adults</option>
                    <option value="5">5+ Adults</option>
                </select>
            </div>

            <div class="hero-search__field">
                <label for="kids">Kids</label>
                <select id="kids" name="kids">
                    <option value="0" selected>0 Kids</option>
                    <option value="1">1 Kid</option>
                    <option value="2">2 Kids</option>
                    <option value="3">3 Kids</option>
                    <option value="4">4+ Kids</option>
                </select>
            </div>

            <button type="submit" class="hero-search__submit" aria-label="Search journeys">
                <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="6" fill="none" stroke="currentColor" stroke-width="2"/>
                    <path d="M16 16l4.5 4.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Search</span>
            </button>
        </form>
    </div>
</section>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        @media (max-width: 860px) {
            .hero-search__field--date {
                width: 100%;
                grid-column: 1 / -1;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const travelDateInput = document.getElementById('travel_date');
            if (!travelDateInput || typeof window.flatpickr !== 'function') {
                return;
            }

            window.flatpickr(travelDateInput, {
                mode: 'range',
                minDate: 'today',
                dateFormat: 'Y-m-d',
                ariaDateFormat: 'F j, Y',
            });
        });
    </script>
@endpush
