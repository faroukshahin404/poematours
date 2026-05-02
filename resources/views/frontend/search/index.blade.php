@extends('frontend.layouts.app')

@section('content')
<section class="packages-hero section packages-results" id="packages">
    <div class="container packages-results__layout">
        <button
            type="button"
            class="packages-filters__toggle"
            data-packages-filters-toggle
            aria-expanded="false"
            aria-controls="packagesFiltersPanel"
        >
            Show Filters
        </button>

        <div
            id="packagesFiltersPanel"
            class="packages-filters__panel"
            data-packages-filters-panel
            aria-hidden="true"
        >
            @include('frontend.packages.partials.filters')
        </div>

        <div class="packages-results__content">
            <div class="packages-results__toolbar">
                <form
                    class="packages-toolbar-search"
                    method="get"
                    action="{{ route('search') }}"
                    role="search"
                >
                    @foreach (request()->query() as $key => $value)
                        @if ($key === 'q')
                            @continue
                        @endif
                        @if (is_array($value))
                            @foreach ($value as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <label class="packages-toolbar-search__field">
                        <span class="sr-only">Search packages by name or destination</span>
                        <svg class="icon icon--sm packages-toolbar-search__icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="M16.5 16.5L21 21" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        <input
                            type="search"
                            name="q"
                            class="packages-toolbar-search__input"
                            value="{{ old('q', request('q')) }}"
                            placeholder="Search packages…"
                            autocomplete="off"
                            enterkeyhint="search"
                        >
                    </label>
                    <button type="submit" class="sr-only">Search</button>
                </form>
                <p class="packages-results__count">{{ $packages->count() }} package(s) found</p>
            </div>

            @includeIf('frontend.packages.views.grid', ['packages' => $packages])
        </div>
    </div>
</section>
@endsection