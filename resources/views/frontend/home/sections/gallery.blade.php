<section class="section">
    <div class="container">
        <p style="margin: 0; color: #9a6c17; letter-spacing: 0.09em; text-transform: uppercase; font-weight: 600; font-size: 0.8rem;">Egypt Through Travelers' Eyes</p>
        <h2 class="section-title">Real moments from people who felt the magic.</h2>
        <div class="grid" style="grid-template-columns: repeat(12, 1fr); margin-top: 1.6rem;">
            <div class="card" style="grid-column: span 7;">
                <img src="{{ asset('assets/images/placeholders/nile-1.avif') }}" alt="Traveler photo near Nile" style="height: 310px; width: 100%; object-fit: cover;">
            </div>
            <div class="card" style="grid-column: span 5;">
                <img src="{{ asset('assets/images/placeholders/sea-3.avif') }}" alt="Travelers by Egyptian sea shore" style="height: 310px; width: 100%; object-fit: cover;">
            </div>
            <div class="card" style="grid-column: span 4;">
                <img src="{{ asset('assets/images/placeholders/nile-3.jpg') }}" alt="Journey moments in ancient temple" style="height: 240px; width: 100%; object-fit: cover;">
            </div>
            <div class="card" style="grid-column: span 4;">
                <img src="{{ asset('assets/images/placeholders/sea-5.jpg') }}" alt="Red Sea relaxation scene" style="height: 240px; width: 100%; object-fit: cover;">
            </div>
            <div class="card" style="grid-column: span 4;">
                <img src="{{ asset('assets/images/placeholders/template-2.avif') }}" alt="Candid travel photo in Egypt" style="height: 240px; width: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        @media (max-width: 900px) {
            .grid > div[style*="grid-column: span 7"],
            .grid > div[style*="grid-column: span 5"],
            .grid > div[style*="grid-column: span 4"] {
                grid-column: span 12 !important;
            }
        }
    </style>
@endpush
