<section class="section">
    <div class="container">
        <div class="home-section-head">
            <p>{{ $homeToursAcrossEgypt['eyebrow'] ?? 'Destinations & Themes' }}</p>
            <h2>{{ $homeToursAcrossEgypt['title'] ?? 'Tours Across Egypt' }}</h2>
        </div>

        <div class="home-tours__grid">
            @foreach (($homeDestinationCards ?? []) as $item)
                <a href="{{ $item['link'] }}" class="home-tours__card" aria-label="{{ $item['title'] ?? 'Destination card' }}">
                    <img src="{{ asset($item['image'] ?? 'assets/images/placeholders/banner.jpeg') }}" alt="{{ $item['title'] ?? 'Destination card' }}">
                    <span>{{ $item['title'] ?? '' }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
