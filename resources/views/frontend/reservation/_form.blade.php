@php
    $formAction = $formAction ?? route('reservation.store');
    $hiddenFields = $hiddenFields ?? [];
    $submitLabel = $submitLabel ?? 'Submit Reservation';
    $submitClass = $submitClass ?? 'customize-btn';
    $showPaymentNote = $showPaymentNote ?? true;
    $travellersDefaultCount = $travellersDefaultCount ?? 2;
    $formId = $formId ?? null;
    $showSubmitButton = $showSubmitButton ?? true;
@endphp

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" novalidate @if($formId) id="{{ $formId }}" @endif>
    @csrf
    @foreach ($hiddenFields as $hiddenName => $hiddenValue)
        <input type="hidden" name="{{ $hiddenName }}" value="{{ $hiddenValue }}">
    @endforeach

    <h2>Contact Information</h2>
    <div class="customize-inline">
        <div class="customize-field">
            <label for="first_name">First Name (required)</label>
            <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required class="@error('first_name') customize-input--invalid @enderror">
            @error('first_name') <p class="customize-field-error">{{ $message }}</p> @enderror
        </div>
        <div class="customize-field">
            <label for="last_name">Last Name (required)</label>
            <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required class="@error('last_name') customize-input--invalid @enderror">
            @error('last_name') <p class="customize-field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="customize-inline">
        <div class="customize-field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" class="@error('email') customize-input--invalid @enderror">
            @error('email') <p class="customize-field-error">{{ $message }}</p> @enderror
        </div>
        <div class="customize-field">
            <label for="contact_phone_number">Contact phone number</label>
            <input id="contact_phone_number" name="contact_phone_number" type="text" value="{{ old('contact_phone_number') }}" class="@error('contact_phone_number') customize-input--invalid @enderror">
            @error('contact_phone_number') <p class="customize-field-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="customize-field">
        <label>Travelers mail (add multiple)</label>
        <div id="traveler-email-list">
            @php($travelerEmails = old('traveler_emails', ['']))
            @foreach ($travelerEmails as $mail)
                <div class="customize-inline traveler-email-row">
                    <div class="customize-field" style="flex:1;">
                        <input type="email" name="traveler_emails[]" value="{{ $mail }}">
                    </div>
                    <button type="button" class="traveler-email-remove" aria-label="Remove email">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>
        <button type="button" id="add-traveler-email" class="customize-btn" style="width:auto;padding:10px 14px;">Add traveler email</button>
        @error('traveler_emails') <p class="customize-field-error">{{ $message }}</p> @enderror
    </div>

    <div class="customize-field">
        <label for="itinerary_cover_name">Whose name is on the cover page of the itinerary we sent? (if not your own)</label>
        <input id="itinerary_cover_name" name="itinerary_cover_name" type="text" value="{{ old('itinerary_cover_name') }}">
    </div>

    <h2>Mailing Address</h2>
    <div class="customize-field"><label for="mailing_street">Street</label><input id="mailing_street" name="mailing_street" type="text" value="{{ old('mailing_street') }}"></div>
    <div class="customize-field"><label for="mailing_street_line_2">Street line 2</label><input id="mailing_street_line_2" name="mailing_street_line_2" type="text" value="{{ old('mailing_street_line_2') }}"></div>
    <div class="customize-inline">
        <div class="customize-field"><label for="mailing_city">City</label><input id="mailing_city" name="mailing_city" type="text" value="{{ old('mailing_city') }}"></div>
        <div class="customize-field"><label for="mailing_state">State</label><input id="mailing_state" name="mailing_state" type="text" value="{{ old('mailing_state') }}"></div>
    </div>
    <div class="customize-inline">
        <div class="customize-field"><label for="mailing_zip_code">Zip code</label><input id="mailing_zip_code" name="mailing_zip_code" type="text" value="{{ old('mailing_zip_code') }}"></div>
        <div class="customize-field"><label for="mailing_country">Country</label><input id="mailing_country" name="mailing_country" type="text" value="{{ old('mailing_country') }}"></div>
    </div>

    <div class="customize-field">
        <label>Possible Mobility Concerns</label>
        @php($mobilityOld = old('mobility_concerns', []))
        <div class="customize-checkboxes">
            @foreach ([
                'stairs' => 'Stairs',
                'walking_pace' => 'Walking pace',
                'walking_distances' => 'Walking distances',
                'hills_or_uneven_paths' => 'Hills or uneven paths',
                'wheelchair_mobility_scooter_stroller_access' => 'Wheelchair or mobility scooter or stroller access',
            ] as $value => $label)
                <label class="customize-checkbox">
                    <input type="checkbox" name="mobility_concerns[]" value="{{ $value }}" @checked(in_array($value, $mobilityOld, true))>
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="customize-field">
        <label for="dietary_restrictions">Dietary restrictions</label>
        <textarea id="dietary_restrictions" name="dietary_restrictions" rows="3">{{ old('dietary_restrictions') }}</textarea>
    </div>

    <div class="customize-field">
        <label for="other_needs_or_requests">Other Needs or Requests</label>
        <textarea id="other_needs_or_requests" name="other_needs_or_requests" rows="3">{{ old('other_needs_or_requests') }}</textarea>
    </div>

    @if (!empty($dynamicQuestions))
        <h2>Additional Questions</h2>
        @foreach ($dynamicQuestions as $question)
            <div class="customize-field">
                <label>{{ $question['title'] }}</label>
                @if (!empty($question['description']))
                    <p style="margin:0 0 8px;">{{ $question['description'] }}</p>
                @endif

                @if ($question['type'] === 'text')
                    <input type="text" name="dynamic_answers[{{ $question['id'] }}]" value="{{ old("dynamic_answers.{$question['id']}") }}">
                @elseif ($question['type'] === 'date')
                    <input type="date" name="dynamic_answers[{{ $question['id'] }}]" value="{{ old("dynamic_answers.{$question['id']}") }}">
                @elseif ($question['type'] === 'multi_select')
                    @php($oldMulti = old("dynamic_answers.{$question['id']}", []))
                    @foreach ($question['options'] as $option)
                        <label class="customize-checkbox">
                            <input
                                type="checkbox"
                                name="dynamic_answers[{{ $question['id'] }}][]"
                                value="{{ $option['label'] }}"
                                data-added-price="{{ (float) ($option['added_price'] ?? 0) }}"
                                @checked(in_array($option['label'], (array) $oldMulti, true))
                            >
                            <span>{{ $option['label'] }} @if($option['added_price'] > 0) (+{{ number_format($option['added_price'], 2) }}) @endif</span>
                        </label>
                    @endforeach
                @else
                    <select name="dynamic_answers[{{ $question['id'] }}]" data-dynamic-select="1">
                        <option value="">Select</option>
                        @foreach ($question['options'] as $option)
                            <option
                                value="{{ $option['label'] }}"
                                data-added-price="{{ (float) ($option['added_price'] ?? 0) }}"
                                @selected(old("dynamic_answers.{$question['id']}") === $option['label'])
                            >
                                {{ $option['label'] }} @if($option['added_price'] > 0) (+{{ number_format($option['added_price'], 2) }}) @endif
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
        @endforeach
    @endif

    <h2>Traveler Information</h2>
    <p>
        This information is used for your domestic flights and Egypt Travel Board registration and insurance.
        If you are only reserving a guided day tour, you may leave everything blank except your names.
    </p>
    <div class="customize-field">
        <label for="travellers_count">Number of travellers</label>
        <input id="travellers_count" name="travellers_count" type="number" min="0" max="50" value="{{ old('travellers_count', $travellersDefaultCount) }}">
    </div>

    <div id="travellers-container"></div>

    <h2>Flight Information</h2>
    <p>Enter your international arrival and departure flight details here so we can arrange your airport greeter and driver accordingly.</p>
    @php($flightOption = old('flight_option'))
    <div class="customize-field">
        <label class="customize-checkbox"><input type="radio" name="flight_option" value="enter_now" @checked($flightOption === 'enter_now')> <span>I have international flight tickets and will enter details now</span></label>
        <label class="customize-checkbox"><input type="radio" name="flight_option" value="send_later" @checked($flightOption === 'send_later')> <span>I will send flight details later</span></label>
        <label class="customize-checkbox"><input type="radio" name="flight_option" value="no_transportation" @checked($flightOption === 'no_transportation')> <span>I do not require airport transportation</span></label>
        <label class="customize-checkbox"><input type="radio" name="flight_option" value="other" @checked($flightOption === 'other')> <span>Other</span></label>
    </div>

    <div id="flight-enter-now-fields" style="display:none;">
        <div class="customize-inline">
            <div class="customize-field"><label>Arrival flight date</label><input type="date" name="arrival_flight_date" value="{{ old('arrival_flight_date') }}"></div>
            <div class="customize-field"><label>Arrival time</label><input type="time" name="arrival_flight_time" value="{{ old('arrival_flight_time') }}"></div>
        </div>
        <div class="customize-inline">
            <div class="customize-field"><label>Arrival flight airline</label><input type="text" name="arrival_flight_airline" value="{{ old('arrival_flight_airline') }}"></div>
            <div class="customize-field"><label>Arrival flight #</label><input type="text" name="arrival_flight_number" value="{{ old('arrival_flight_number') }}"></div>
        </div>
        <div class="customize-inline">
            <div class="customize-field"><label>Return flight date</label><input type="date" name="return_flight_date" value="{{ old('return_flight_date') }}"></div>
            <div class="customize-field"><label>Return flight departure time (00:00-23:59)</label><input type="time" name="return_flight_departure_time" value="{{ old('return_flight_departure_time') }}"></div>
        </div>
        <div class="customize-inline">
            <div class="customize-field"><label>Return flight airline</label><input type="text" name="return_flight_airline" value="{{ old('return_flight_airline') }}"></div>
            <div class="customize-field"><label>Return flight number</label><input type="text" name="return_flight_number" value="{{ old('return_flight_number') }}"></div>
        </div>
    </div>

    <div id="flight-other-fields" style="display:none;">
        <div class="customize-field">
            <label>Other flight details</label>
            <textarea name="flight_other_text" rows="3">{{ old('flight_other_text') }}</textarea>
        </div>
    </div>

    @if ($showPaymentNote)
        <h2>Payment</h2>
        <p>For credit card payment, you will receive payment instructions by email after reservation is confirmed.</p>
    @endif

    @if ($showSubmitButton)
        <button class="{{ $submitClass }}" type="submit">{{ $submitLabel }}</button>
    @endif
</form>
