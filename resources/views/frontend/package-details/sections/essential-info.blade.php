<section class="package-section" id="essential-info">
    <div class="container essential-info">
        <h2>Essential Info</h2>

        <div class="essential-info__accordion" data-essential-accordion>
            @foreach($details['essential_info'] as $info)
                <article class="essential-info__item {{ $loop->index === 2 ? 'is-open' : '' }}" data-essential-item>
                    <button type="button" class="essential-info__question" data-essential-toggle>
                        <span>{{ $info['question'] }}</span>
                        <span class="essential-info__chevron">&#8964;</span>
                    </button>
                    <div class="essential-info__answer">
                        <p>{{ $info['answer'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
