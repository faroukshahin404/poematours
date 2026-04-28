@extends('frontend.layouts.app')

@section('content')
    <section class="gallery-page booking-page">
        <div class="container gallery-page__head">
            <nav class="packages-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('packages.index') }}">Travel Destinations</a>
                <span>/</span>
                <a href="{{ route('packages.show', $package['slug']) }}">{{ $package['title'] }}</a>
                <span>/</span>
                <span>Passenger Details</span>
            </nav>
            <h1>Passenger Details</h1>
            <p>Complete traveler information and confirm payment.</p>
        </div>

        <div class="container booking-layout">
            <form class="booking-form" action="#" method="POST">
                <section class="booking-form__section">
                    <h2>Lead Passenger</h2>
                    <div class="booking-form__grid">
                        <select required>
                            <option value="">Title</option>
                            <option>Mr.</option>
                            <option>Ms.</option>
                            <option>Mrs.</option>
                            <option>Dr.</option>
                        </select>
                        <input type="text" placeholder="First Name*" required>
                        <input type="text" placeholder="Middle Name">
                        <input type="text" placeholder="Last Name*" required>
                        <select required>
                            <option value="">Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                        <input type="date" placeholder="Date of Birth*" required>
                        <input type="email" placeholder="Email Address*" required>
                        <input type="tel" placeholder="Phone Number*" required>
                        <input type="text" class="booking-form__full" placeholder="Billing Address*" required>
                    </div>
                </section>

                <section class="booking-form__section">
                    <div class="booking-form__section-head">
                        <h2>Other Passengers</h2>
                        <button type="button" data-add-passenger>Add Passenger</button>
                    </div>
                    <div id="otherPassengers" data-other-passengers data-initial-count="{{ max($adultsCount - 1, 0) }}"></div>
                </section>

                <section class="booking-form__section">
                    <h2>Notes</h2>
                    <textarea rows="4" placeholder="Add booking notes"></textarea>
                </section>
            </form>

            <aside class="booking-summary">
                <h3>Trip Summary</h3>
                <p><strong>Package:</strong> {{ $package['title'] }}</p>
                <p><strong>Destination:</strong> {{ $package['destination'] }}</p>
                @if($departure)
                    <p><strong>Dates:</strong> {{ $departure['period'] }}</p>
                    <p><strong>Price:</strong> ${{ number_format($departure['price']) }} per person</p>
                    <p><strong>Single Supplement:</strong> ${{ number_format($departure['single_supplement']) }}</p>
                @else
                    <p><strong>Dates:</strong> To be selected</p>
                @endif
                <p><strong>Adults:</strong> {{ $adultsCount }}</p>
                <hr>
                <p class="booking-summary__total"><strong>Total:</strong>
                    @if($departure)
                        ${{ number_format($departure['price'] * $adultsCount) }}
                    @else
                        -
                    @endif
                </p>

                <div class="booking-summary__payment">
                    <h4>Payment Method</h4>
                    <label><input type="radio" checked> Stripe</label>
                    <button type="button" class="booking-summary__confirm">Confirm Payment</button>
                </div>
            </aside>
        </div>
    </section>
@endsection
