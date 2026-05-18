@php
    $whyChooseItems = [
        [
            'icon' => 'assets/icons/plane.webp',
            'label' => __('Comfortable and luxurious travel'),
        ],
        [
            'icon' => 'assets/icons/map.webp',
            'label' => __('Highlight-filled itineraries designed by experts'),
        ],
        [
            'icon' => 'assets/icons/diamond.webp',
            'label' => __('Unique and exclusive experiences in every destination'),
        ],
        [
            'icon' => 'assets/icons/key.webp',
            'label' => __('The best luxury accommodations in every destination'),
        ],
        [
            'icon' => 'assets/icons/calendar.webp',
            'label' => __('Convenience of pre-set dates'),
        ],
    ];
@endphp

<section class="package-why-choose" aria-labelledby="package-why-choose-heading">
    <div class="container package-why-choose__inner">
        <h2 id="package-why-choose-heading" class="package-why-choose__title">
            {{ __('Why choose a Poematours private ready-to-book journey?') }}
        </h2>

        <ul class="package-why-choose__grid" role="list">
            @foreach ($whyChooseItems as $item)
                <li class="package-why-choose__item">
                    <img
                        class="package-why-choose__icon"
                        src="{{ asset($item['icon']) }}"
                        alt=""
                        width="80"
                        height="80"
                        loading="lazy"
                        decoding="async"
                    >
                    <p class="package-why-choose__text">{{ $item['label'] }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</section>
