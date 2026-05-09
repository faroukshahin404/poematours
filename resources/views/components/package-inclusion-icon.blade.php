@props(['icon' => ''])

@php
    use App\Support\HeroiconOutlineSlug;
    use Illuminate\Support\Str;

    $icon = trim((string) $icon);
@endphp

@if($icon !== '')
    <span {{ $attributes->merge(['class' => 'dates-prices-info__inclusion-icon'])->except('icon') }} aria-hidden="true">
        @if(Str::startsWith($icon, ['http://', 'https://', '//']))
            <img
                src="{{ $icon }}"
                alt=""
                class="dates-prices-info__inclusion-img"
                loading="lazy"
                decoding="async"
                width="20"
                height="20"
            />
        @elseif(str_starts_with($icon, 'storage/') || str_starts_with($icon, 'assets/'))
            <img
                src="{{ asset($icon) }}"
                alt=""
                class="dates-prices-info__inclusion-img"
                loading="lazy"
                decoding="async"
                width="20"
                height="20"
            />
        @else
            @php $slug = HeroiconOutlineSlug::fromAdminIconField($icon); @endphp
            <img
                src="https://cdn.jsdelivr.net/npm/heroicons@2.2.0/24/outline/{{ $slug }}.svg"
                alt=""
                class="dates-prices-info__inclusion-img dates-prices-info__inclusion-img--hero"
                loading="lazy"
                decoding="async"
                width="20"
                height="20"
            />
        @endif
    </span>
@endif
