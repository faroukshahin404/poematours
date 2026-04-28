<section class="package-section" id="ship">
    <div class="container package-ship">
        <img src="{{ asset($details['ship']['image']) }}" alt="{{ $details['ship']['name'] }}">
        <div>
            <h2>About the Ship</h2>
            <h3>{{ $details['ship']['name'] }}</h3>
            <p>{{ $details['ship']['description'] }}</p>
        </div>
    </div>
</section>
