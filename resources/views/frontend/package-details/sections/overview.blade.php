<section class="package-section" id="overview">
    <div class="container package-overview">
        <div class="package-overview__content">

            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('packages.index') }}">Travel Destinations</a>
                <span>/</span>
                <span>{{ $package['title'] }}</span>
            </nav>
            {{-- Overview strings are localized + sanitized HTML from PackageSearchService --}}
            <h2>{!! $details['overview']['title'] ?? e($package['title']) !!}</h2>
            <p>{!! $details['overview']['intro'] ?? e($package['description']) !!}</p>
            <p class="package-overview__lead">
                {!! $details['overview']['lead'] ?? e($package['description']) !!}
            </p>
            <p class="package-overview__support">
                {!! $details['overview']['support'] ?? '' !!}
            </p>
            <ul class="package-overview__highlights">
                @foreach(($details['overview']['highlights'] ?? []) as $highlight)
                    <li>{!! $highlight !!}</li>
                @endforeach
            </ul>
            @if(!empty($package['pdf_url']))
                <a href="{{ $package['pdf_url'] }}" target="_blank" class="package-download">
                    <svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v10m0 0l-4-4m4 4l4-4M5 18h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    <span>{{ __('Download PDF') }}</span>
                </a>
            @endif
        </div>
        <div class="package-overview__gallery" data-detail-gallery>
            @foreach($details['overview']['gallery'] as $image)
                <button type="button" class="package-overview__image" data-lightbox-trigger data-src="{{ asset($image) }}">
                    <img src="{{ asset($image) }}" alt="Package overview image {{ $loop->iteration }}">
                </button>
            @endforeach
        </div>
    </div>
</section>
