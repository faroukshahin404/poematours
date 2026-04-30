<article class="place-visit-card">
    <img src="{{ asset($place['image']) }}" alt="{{ $place['title'] }}">
    <div class="place-visit-card__body">
        <h3>{{ $place['title'] }}</h3>
        <p>{{ $place['description'] }}</p>
    </div>
</article>
