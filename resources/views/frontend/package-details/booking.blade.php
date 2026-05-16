@extends('frontend.layouts.app')

@push('styles')
    <style>
        .booking-page {
            background: var(--color-009);
        }

        .booking-page .booking-form,
        .booking-page .booking-summary {
            background: #fff;
            border: 1px solid #dadce0;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(60, 64, 67, 0.08);
            padding: 24px;
        }

        .booking-page .booking-form__section {
            padding: 0 0 20px;
            margin: 0 0 20px;
            border-bottom: 1px solid #eceff1;
        }

        .booking-page .booking-form__section:last-of-type {
            border-bottom: 0;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .booking-page .booking-form__section h2 {
            margin-bottom: 14px;
            font-size: 1.05rem;
            color: #1f2937;
        }

        .booking-page .booking-form h2 {
            margin-top: 26px;
            padding-top: 22px;
            border-top: 1px solid #eceff1;
        }

        .booking-page .booking-form h2:first-of-type {
            margin-top: 0;
            padding-top: 0;
            border-top: 0;
        }

        .booking-page .booking-form label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #334155;
        }

        .booking-page .booking-form input[type="text"],
        .booking-page .booking-form input[type="email"],
        .booking-page .booking-form input[type="tel"],
        .booking-page .booking-form input[type="date"],
        .booking-page .booking-form input[type="number"],
        .booking-page .booking-form input[type="file"],
        .booking-page .booking-form textarea,
        .booking-page .booking-form select {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #dadce0;
            background: #fff;
            color: #0f172a;
            padding: 10px 12px;
            font-size: 0.95rem;
            line-height: 1.3;
        }

        .booking-page .booking-form input:focus,
        .booking-page .booking-form textarea:focus,
        .booking-page .booking-form select:focus {
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.14);
            outline: none;
        }

        .booking-page .booking-form input[type="radio"],
        .booking-page .booking-form input[type="checkbox"] {
            width: 16px;
            height: 16px;
            min-width: 16px;
            min-height: 16px;
            margin: 0;
            accent-color: #111827;
            transform: none;
        }

        .booking-page .booking-form .booking-form__grid>label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            padding: 10px 12px;
            border: 1px solid #dadce0;
            border-radius: 8px;
            font-size: 0.92rem;
            font-weight: 500;
            color: #334155;
        }

        .booking-page .booking-summary {
            position: sticky;
            top: 120px;
            border: 0;
            background: linear-gradient(160deg, #ffffff 0%, #f7f7f8 45%, #f1f5f9 100%);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .booking-page .booking-summary::before {
            content: '';
            position: absolute;
            inset: 0 0 auto 0;
            height: 5px;
            background: #111827;
        }

        .booking-page .booking-summary h3 {
            margin: 8px 0 16px;
            font-size: 1.2rem;
            color: #0f172a;
            letter-spacing: 0.01em;
        }

        .booking-page .booking-summary__line {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 0 0 10px;
            font-size: 0.95rem;
            color: #334155;
        }

        .booking-page .booking-summary__line strong {
            font-weight: 600;
            color: #0f172a;
        }

        .booking-page .booking-summary__line span {
            text-align: right;
        }

        .booking-page .booking-summary hr {
            margin: 16px 0;
            border: 0;
            border-top: 1px solid #dbe4f0;
        }

        .booking-page .booking-summary__total {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin: 0 0 8px;
            color: #0f172a;
        }

        .booking-page .booking-summary__total strong {
            font-size: 0.98rem;
        }

        .booking-page .booking-summary__total span {
            font-size: 1.04rem;
            font-weight: 700;
            color: #0f172a;
        }

        .booking-page .booking-summary__total--deposit span {
            color: #111827;
            font-size: 1.15rem;
        }

        .booking-page .booking-summary__badge {
            margin: 14px 0 18px;
            padding: 10px 12px;
            border: 1px solid #ccfbf1;
            background: #f0fdfa;
            border-radius: 10px;
            font-size: 0.84rem;
            color: #115e59;
            line-height: 1.45;
        }

        .booking-page .booking-summary__payment {
            margin-top: 6px;
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
        }

        .booking-page .booking-summary__payment h4 {
            margin: 0 0 8px;
            font-size: 0.95rem;
            color: #0f172a;
        }

        .booking-page .booking-summary__payment label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .booking-page .booking-summary__confirm {
            width: 100%;
            margin-top: 16px;
            padding: 13px 18px;
            border: 0;
            border-radius: 10px;
            color: #fff;
            font-weight: 700;
            font-size: 0.98rem;
            letter-spacing: 0.01em;
            background: #111827;
            box-shadow: 0 10px 22px rgba(17, 24, 39, 0.28);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .booking-page .booking-summary__confirm:hover {
            transform: translateY(-1px);
            background: #000;
            box-shadow: 0 14px 26px rgba(17, 24, 39, 0.35);
        }

        .booking-page .booking-summary__confirm:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .booking-page .booking-summary__error {
            margin: 12px 0 0;
            font-size: 0.85rem;
            color: #b91c1c;
            line-height: 1.45;
        }
    </style>
@endpush

@section('content')
    <section class="booking-page page-shell-offset">


        <div class="container booking-layout">
            <div>
                <div class="reservation-heading">
                    <h1>Reservation Form</h1>
                    <p>Please complete the reservation details. You can submit partial traveller and flight details now
                        and update later.</p>
                </div>
                <div class="booking-form reservation-form-card">

                    @include('frontend.reservation._form', [
                        'formAction' => route('reservation.store'),
                        'formId' => 'packageBookingForm',
                        'hiddenFields' => [
                            'booking_source' => 'package',
                            'package_id' => request('package_id', $package['id'] ?? null),
                            'package_date_price_id' => request('package_date_price_id', request('departure_id')),
                            'unit_price' => request('unit_price', $departure['price'] ?? 0),
                            'payment_status' => 'partially_paid',
                            'booking_status' => 'pending',
                            'paid_amount' => '',
                            'total_amount' => '',
                        ],
                        'submitLabel' => 'Confirm and Pay 20%',
                        'submitClass' => 'booking-summary__confirm',
                        'showSubmitButton' => false,
                        'showPaymentNote' => false,
                        'travellersDefaultCount' => $adultsCount,
                    ])
                </div>
            </div>

            <aside class="booking-summary">
                <h3>Trip Summary</h3>
                <p class="booking-summary__line"><strong>Package:</strong> <span>{{ $package['title'] }}</span></p>
                @if ($departure)
                    <p class="booking-summary__line"><strong>Dates:</strong> <span>{{ $departure['period'] }}</span></p>
                    <p class="booking-summary__line"><strong>Price:</strong> <span>${{ number_format($departure['price']) }} per person</span></p>
                    <p class="booking-summary__line"><strong>Single Supplement:</strong> <span>${{ number_format($departure['single_supplement']) }}</span></p>
                @else
                    <p class="booking-summary__line"><strong>Dates:</strong> <span>To be selected</span></p>
                @endif
                <p class="booking-summary__line"><strong>Adults:</strong> <span>{{ $adultsCount }}</span></p>
                <p class="booking-summary__line"><strong>Addons:</strong> <span id="bookingAddonsDisplay">$0.00</span></p>
                <hr>
                <p class="booking-summary__total"><strong>Total:</strong> <span id="bookingTotalDisplay">-</span></p>
                <p class="booking-summary__total booking-summary__total--deposit"><strong>Pay now (20%):</strong> <span id="bookingDepositDisplay">-</span>
                </p>
                
                <button type="submit" class="booking-summary__confirm" form="packageBookingForm">
                    <svg width="26" height="16" viewBox="0 0 52 32" fill="none" aria-hidden="true">
                        <rect width="52" height="32" rx="6" fill="white" fill-opacity="0.15" />
                        <path d="M12.5 19.9c0 1.9 1.6 2.6 3.6 2.6 2.1 0 3.6-.9 3.6-2.9 0-1.4-.8-2.2-2.8-2.7l-1.2-.3c-.8-.2-1.1-.4-1.1-.8 0-.5.5-.8 1.3-.8.9 0 1.9.2 2.8.7v-2.2c-.8-.4-1.8-.6-3-.6-2 0-3.4 1-3.4 2.8 0 1.3.7 2.1 2.5 2.5l1.2.3c1 .2 1.3.5 1.3 1 0 .5-.4.8-1.3.8-1 0-2.2-.3-3.2-.8v2.4z" fill="white"/>
                        <path d="M24.2 13.2h-2.4v9h2.4v-9zm0-3.2h-2.4v2.2h2.4V10z" fill="white"/>
                        <path d="M29.9 15.3c1 0 1.6.3 2.3.8v-2.5c-.7-.3-1.5-.5-2.5-.5-2.7 0-4.4 1.7-4.4 4.6 0 2.8 1.7 4.6 4.4 4.6 1 0 1.8-.2 2.5-.5v-2.5c-.7.5-1.3.8-2.3.8-1.3 0-2.1-.8-2.1-2.3 0-1.6.8-2.5 2.1-2.5z" fill="white"/>
                        <path d="M37.9 13.1c-2.6 0-4.4 1.8-4.4 4.6 0 2.8 1.8 4.6 4.4 4.6 2.6 0 4.4-1.8 4.4-4.6 0-2.8-1.8-4.6-4.4-4.6zm0 7c-1.2 0-2-.9-2-2.4 0-1.5.8-2.4 2-2.4s2 .9 2 2.4c0 1.5-.8 2.4-2 2.4z" fill="white"/>
                    </svg>
                    Confirm and Pay 20%
                </button>
                @error('payment')
                    <p class="booking-summary__error">{{ $message }}</p>
                @enderror
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
    @include('frontend.reservation._form_scripts')
    <script>
        (function() {
            const travellersCountInput = document.getElementById('travellers_count');
            const unitPriceInput = document.querySelector('input[name="unit_price"]');
            const totalAmountInput = document.querySelector('input[name="total_amount"]');
            const paidAmountInput = document.querySelector('input[name="paid_amount"]');
            const totalDisplay = document.getElementById('bookingTotalDisplay');
            const depositDisplay = document.getElementById('bookingDepositDisplay');
            const addonsDisplay = document.getElementById('bookingAddonsDisplay');
            const bookingForm = document.getElementById('packageBookingForm');

            function numberFromDataset(value) {
                const parsed = Number(value || 0);
                return Number.isFinite(parsed) ? parsed : 0;
            }

            function dynamicAddonsPerPerson() {
                if (!bookingForm) {
                    return 0;
                }

                let total = 0;

                bookingForm.querySelectorAll('input[type="checkbox"][data-added-price]:checked').forEach((checkbox) => {
                    total += numberFromDataset(checkbox.dataset.addedPrice);
                });

                bookingForm.querySelectorAll('select[data-dynamic-select] option:checked[data-added-price]').forEach((option) => {
                    total += numberFromDataset(option.dataset.addedPrice);
                });

                return total;
            }

            function updatePrice() {
                const pax = Math.max(1, Number(travellersCountInput?.value || 1));
                const unitPrice = Number(unitPriceInput?.value || 0);
                const addonsPerPerson = dynamicAddonsPerPerson();
                const addons = addonsPerPerson * pax;
                const total = (unitPrice * pax) + addons;
                const deposit = total * 0.2;

                if (totalAmountInput) totalAmountInput.value = total.toFixed(2);
                if (paidAmountInput) paidAmountInput.value = deposit.toFixed(2);
                if (addonsDisplay) addonsDisplay.textContent =
                    `$${addons.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                if (totalDisplay) totalDisplay.textContent =
                    `$${total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                if (depositDisplay) depositDisplay.textContent =
                    `$${deposit.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            }

            travellersCountInput?.addEventListener('input', updatePrice);
            bookingForm?.addEventListener('change', updatePrice);
            updatePrice();
        })();
    </script>
@endpush
