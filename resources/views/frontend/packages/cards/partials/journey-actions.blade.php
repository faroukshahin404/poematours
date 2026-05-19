@php
    $journeyButtonClass = $journeyButtonClass ?? 'package-card__button package-card__button--outline';
@endphp
<div class="package-journey-dropdown" data-package-journey-dropdown>
    <button
        type="button"
        class="package-journey-dropdown__toggle {{ $journeyButtonClass }}"
        data-package-journey-toggle
        aria-expanded="false"
        aria-haspopup="true"
    >
        <span>View Journey</span>
        <svg class="icon icon--sm package-journey-dropdown__chevron" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </button>
    <div class="package-journey-dropdown__menu" role="menu" aria-label="Journey actions" aria-hidden="true">
        <a href="{{ route('packages.show', $package['slug']) }}" class="package-journey-dropdown__link" role="menuitem">
            View Journey
        </a>
        <a
            href="{{ route('customize.create', ['package_id' => $package['id']]) }}"
            class="package-journey-dropdown__link"
            role="menuitem"
        >
            Customize Journey
        </a>
    </div>
</div>
