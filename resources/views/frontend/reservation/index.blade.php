@extends('frontend.layouts.app')

@section('content')
    <section class="customize-hero">
        <div class="container">
            <h1>Reserve Your Egypt Journey</h1>
            <p>Share your travel details, choose optional add-ons, and pay your deposit securely.</p>
        </div>
    </section>

    <section class="customize-page">
        <div class="container customize-grid">
            <div class="customize-card">
                <div id="reservation-error" class="customize-alert customize-alert--error" style="display:none;"></div>

                <form id="reservation-form" method="POST" novalidate>
                    @csrf
                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="full_name">Full name</label>
                            <input id="full_name" type="text" name="full_name" required>
                        </div>
                        <div class="customize-field">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" required>
                        </div>
                    </div>

                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="phone">Phone</label>
                            <input id="phone" type="text" name="phone">
                        </div>
                        <div class="customize-field">
                            <label for="country">Country</label>
                            <input id="country" type="text" name="country">
                        </div>
                    </div>

                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="adults">Adults</label>
                            <input id="adults" type="number" min="1" name="adults" value="1" required>
                        </div>
                        <div class="customize-field">
                            <label for="children">Children</label>
                            <input id="children" type="number" min="0" name="children" value="0">
                        </div>
                    </div>

                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="arrival_date">Arrival date</label>
                            <input id="arrival_date" type="date" name="arrival_date">
                        </div>
                        <div class="customize-field">
                            <label for="departure_date">Departure date</label>
                            <input id="departure_date" type="date" name="departure_date">
                        </div>
                    </div>

                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="duration_days">Duration (days)</label>
                            <input id="duration_days" type="number" min="1" name="duration_days">
                        </div>
                        <div class="customize-field">
                            <label for="estimated_total">Estimated total price ({{ $currency }})</label>
                            <input id="estimated_total" type="number" step="0.01" min="0" name="estimated_total" required>
                        </div>
                    </div>

                    <div class="customize-field">
                        <label for="destinations">Preferred destinations</label>
                        <input id="destinations" type="text" name="destinations" placeholder="Cairo, Luxor, Aswan...">
                    </div>

                    @if (count($addonGroups) > 0)
                        <div class="customize-field">
                            <label>Select Optional Tours / Add-ons</label>
                            @foreach ($addonGroups as $group)
                                @php
                                    $groupCode = $group['code'] ?? '';
                                    $selectionType = $group['selection_type'] ?? 'multiple';
                                @endphp
                                <div class="customize-checkboxes" style="margin-bottom:12px;">
                                    <p style="margin:0 0 8px;font-weight:600;">{{ $group['title'] ?? $groupCode }} @if(($group['is_required'] ?? false)) * @endif</p>
                                    @foreach (($group['options'] ?? []) as $option)
                                        @php
                                            $optionPrice = number_format((float) ($option['price'] ?? 0), 2);
                                            $priceType = ($option['price_type'] ?? 'flat') === 'per_person' ? 'per person' : 'flat';
                                        @endphp
                                        <label class="customize-checkbox">
                                            <input
                                                type="{{ $selectionType === 'single' ? 'radio' : 'checkbox' }}"
                                                name="addons[{{ $groupCode }}]{{ $selectionType === 'single' ? '' : '[]' }}"
                                                value="{{ $option['code'] ?? '' }}"
                                                data-addon-price="{{ $option['price'] ?? 0 }}"
                                                data-addon-price-type="{{ $option['price_type'] ?? 'flat' }}"
                                            >
                                            <span>{{ $option['label'] ?? '' }}: {{ $currency }} {{ $optionPrice }} ({{ $priceType }})</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="customize-binary-options">
                        <label class="customize-checkbox">
                            <input type="checkbox" name="need_transfers" value="1">
                            <span>I need airport/hotel transfers</span>
                        </label>
                        <label class="customize-checkbox">
                            <input type="checkbox" name="need_domestic_flights" value="1">
                            <span>I need domestic flights arranged</span>
                        </label>
                    </div>

                    <div class="customize-field">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" rows="4"></textarea>
                    </div>

                    <div id="payment-element" style="display:none;margin-bottom:12px;"></div>

                    <button id="reservation-submit" class="customize-btn" type="submit">Continue to secure payment</button>
                </form>
            </div>

            <aside class="customize-card customize-side">
                <h2>Payment summary</h2>
                <ul>
                    <li>Base estimate: <strong id="summary-base">{{ $currency }} 0.00</strong></li>
                    <li>Add-ons total: <strong id="summary-addons">{{ $currency }} 0.00</strong></li>
                    <li>Total estimate: <strong id="summary-total">{{ $currency }} 0.00</strong></li>
                    <li>Deposit ({{ $depositPercentage }}%): <strong id="summary-deposit">{{ $currency }} 0.00</strong></li>
                </ul>
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        (() => {
            const form = document.getElementById('reservation-form');
            const submitButton = document.getElementById('reservation-submit');
            const errorBox = document.getElementById('reservation-error');
            const paymentElement = document.getElementById('payment-element');
            const baseInput = document.getElementById('estimated_total');
            const adultsInput = document.getElementById('adults');
            const childrenInput = document.getElementById('children');
            const addonInputs = Array.from(document.querySelectorAll('input[name^="addons["]'));
            const currency = @json($currency);
            const depositPercentage = @json((int) $depositPercentage);
            let stripe = null;
            let elements = null;
            let paymentReady = false;
            let reservationUuid = null;

            const formatMoney = (value) => `${currency} ${value.toFixed(2)}`;
            const asNumber = (value) => Number.parseFloat(value || '0') || 0;
            const travelers = () => Math.max(1, (parseInt(adultsInput.value || '1', 10) || 1) + (parseInt(childrenInput.value || '0', 10) || 0));

            const updateSummary = () => {
                const base = asNumber(baseInput.value);
                const pax = travelers();
                let addons = 0;
                for (const input of addonInputs) {
                    if (!input.checked) {
                        continue;
                    }
                    const price = asNumber(input.dataset.addonPrice);
                    addons += input.dataset.addonPriceType === 'per_person' ? price * pax : price;
                }
                const total = base + addons;
                const deposit = total * (depositPercentage / 100);

                document.getElementById('summary-base').textContent = formatMoney(base);
                document.getElementById('summary-addons').textContent = formatMoney(addons);
                document.getElementById('summary-total').textContent = formatMoney(total);
                document.getElementById('summary-deposit').textContent = formatMoney(deposit);
            };

            const showError = (message) => {
                errorBox.style.display = 'block';
                errorBox.textContent = message;
            };

            const clearError = () => {
                errorBox.style.display = 'none';
                errorBox.textContent = '';
            };

            const submitIntent = async () => {
                clearError();
                const formData = new FormData(form);
                const response = await fetch(@json(route('reservation.intent')), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const payload = await response.json();
                if (!response.ok) {
                    if (payload.errors) {
                        const firstError = Object.values(payload.errors)[0];
                        showError(Array.isArray(firstError) ? firstError[0] : 'Validation failed.');
                    } else {
                        showError(payload.message || 'Unable to start payment.');
                    }
                    return null;
                }
                return payload;
            };

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                submitButton.disabled = true;
                try {
                    if (!paymentReady) {
                        const intent = await submitIntent();
                        if (!intent) {
                            return;
                        }
                        reservationUuid = intent.reservation_uuid;
                        stripe = Stripe(intent.publishable_key);
                        elements = stripe.elements({ clientSecret: intent.client_secret });
                        elements.create('payment').mount('#payment-element');
                        paymentElement.style.display = 'block';
                        submitButton.textContent = 'Pay deposit now';
                        paymentReady = true;
                        return;
                    }

                    const result = await stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            return_url: `${@json(route('reservation.success'))}?reservation=${encodeURIComponent(reservationUuid || '')}`,
                        },
                    });

                    if (result.error) {
                        showError(result.error.message || 'Payment failed. Please try again.');
                    }
                } catch (e) {
                    showError('Unexpected error. Please try again.');
                } finally {
                    submitButton.disabled = false;
                }
            });

            [baseInput, adultsInput, childrenInput, ...addonInputs].forEach((element) => {
                element.addEventListener('input', updateSummary);
                element.addEventListener('change', updateSummary);
            });

            updateSummary();
        })();
    </script>
@endpush
