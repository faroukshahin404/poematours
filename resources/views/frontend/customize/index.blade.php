@extends('frontend.layouts.app')

@section('content')
    <section class="customize-hero">
        <div class="container">
            <h1>Customize Your Egypt Journey</h1>
            <p>
                Share your preferred travel style, destinations, and budget. Our Egypt specialists will craft a private
                plan that matches your pace, whether you want ancient history, Nile cruising, Red Sea relaxation, or desert adventures.
            </p>
        </div>
    </section>

    <section class="customize-page">
        <div class="container customize-grid">
            <div class="customize-card">
                @if (session('status'))
                    <div class="customize-alert customize-alert--success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="customize-alert customize-alert--error">
                        Please review the highlighted fields and try again.
                    </div>
                @endif

                <form action="{{ route('customize.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="full_name">Full name</label>
                            <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" placeholder="Optional" class="@error('full_name') customize-input--invalid @enderror">
                            @error('full_name')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="customize-field">
                            <label for="preferred_contact_method">Preferred contact method</label>
                            <select id="preferred_contact_method" name="preferred_contact_method" class="@error('preferred_contact_method') customize-input--invalid @enderror">
                                <option value="">No preference</option>
                                <option value="email" @selected(old('preferred_contact_method') === 'email')>Email</option>
                                <option value="phone" @selected(old('preferred_contact_method') === 'phone')>Phone call</option>
                                <option value="whatsapp" @selected(old('preferred_contact_method') === 'whatsapp')>WhatsApp</option>
                            </select>
                            @error('preferred_contact_method')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="email">Email </label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" class="@error('email') customize-input--invalid @enderror">
                            @error('email')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="customize-field">
                            <label for="phone">Phone</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 ..." class="@error('phone') customize-input--invalid @enderror">
                            @error('phone')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="adults">Adults</label>
                            <input id="adults" type="number" min="1" name="adults" value="{{ old('adults') }}" placeholder="Optional" class="@error('adults') customize-input--invalid @enderror">
                            @error('adults')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="customize-field">
                            <label for="children">Children</label>
                            <input id="children" type="number" min="0" name="children" value="{{ old('children') }}" placeholder="Optional" class="@error('children') customize-input--invalid @enderror">
                            @error('children')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="arrival_date">Arrival date</label>
                            <input id="arrival_date" type="date" name="arrival_date" value="{{ old('arrival_date') }}" class="@error('arrival_date') customize-input--invalid @enderror">
                            @error('arrival_date')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="customize-field">
                            <label for="departure_date">Departure date</label>
                            <input id="departure_date" type="date" name="departure_date" value="{{ old('departure_date') }}" class="@error('departure_date') customize-input--invalid @enderror">
                            @error('departure_date')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="customize-inline">
                        <div class="customize-field">
                            <label for="duration_days">Duration (days)</label>
                            <input id="duration_days" type="number" min="1" name="duration_days" value="{{ old('duration_days') }}" placeholder="Optional" class="@error('duration_days') customize-input--invalid @enderror">
                            @error('duration_days')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="customize-field">
                            <label for="budget_range">Budget range</label>
                            <select id="budget_range" name="budget_range" class="@error('budget_range') customize-input--invalid @enderror">
                                <option value="">Not specified</option>
                                <option value="economy" @selected(old('budget_range') === 'economy')>Economy</option>
                                <option value="mid-range" @selected(old('budget_range') === 'mid-range')>Mid-range</option>
                                <option value="premium" @selected(old('budget_range') === 'premium')>Premium</option>
                                <option value="luxury" @selected(old('budget_range') === 'luxury')>Luxury</option>
                            </select>
                            @error('budget_range')
                                <p class="customize-field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="customize-field">
                        <label for="destinations">Preferred Egyptian destinations</label>
                        <input id="destinations" type="text" name="destinations" value="{{ old('destinations') }}" placeholder="Cairo, Luxor, Aswan, Siwa, Hurghada ..." class="@error('destinations') customize-input--invalid @enderror">
                        @error('destinations')
                            <p class="customize-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="customize-field">
                        <label>Travel interests</label>
                        <div class="customize-checkboxes @if($errors->has('interests') || $errors->has('interests.*')) customize-checkboxes--invalid @endif">
                            @php
                                $interestOptions = [
                                    'history' => 'Ancient history',
                                    'nile-cruise' => 'Nile cruise',
                                    'desert-adventure' => 'Desert adventure',
                                    'red-sea' => 'Red Sea beaches/diving',
                                    'family-time' => 'Family-friendly experiences',
                                    'honeymoon' => 'Honeymoon',
                                    'luxury' => 'Luxury stays',
                                    'culture-food' => 'Local culture and food',
                                ];
                                $oldInterests = old('interests', []);
                            @endphp
                            @foreach ($interestOptions as $value => $label)
                                <label class="customize-checkbox">
                                    <input type="checkbox" name="interests[]" value="{{ $value }}" @checked(in_array($value, $oldInterests, true))>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('interests')
                            <p class="customize-field-error">{{ $message }}</p>
                        @enderror
                        @error('interests.*')
                            <p class="customize-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="customize-binary-options">
                        <label class="customize-checkbox">
                            <input type="checkbox" name="need_guide" value="1" @checked(old('need_guide'))>
                            <span>I need a private guide</span>
                        </label>
                        <label class="customize-checkbox">
                            <input type="checkbox" name="need_transportation" value="1" @checked(old('need_transportation'))>
                            <span>I need transportation planning</span>
                        </label>
                    </div>

                    <div class="customize-field">
                        <label for="accommodation_style">Accommodation style</label>
                        <select id="accommodation_style" name="accommodation_style" class="@error('accommodation_style') customize-input--invalid @enderror">
                            <option value="">Not specified</option>
                            <option value="boutique" @selected(old('accommodation_style') === 'boutique')>Boutique</option>
                            <option value="international-5-star" @selected(old('accommodation_style') === 'international-5-star')>International 5-star</option>
                            <option value="nile-cruise-mix" @selected(old('accommodation_style') === 'nile-cruise-mix')>Nile cruise + hotels</option>
                            <option value="family-suite" @selected(old('accommodation_style') === 'family-suite')>Family suites</option>
                        </select>
                        @error('accommodation_style')
                            <p class="customize-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="customize-field">
                        <label for="notes">Tell us anything important</label>
                        <textarea id="notes" name="notes" rows="5" placeholder="Accessibility needs, pacing preference, special occasions, dietary notes..." class="@error('notes') customize-input--invalid @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="customize-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="customize-btn" type="submit">Send request</button>
                    
                </form>
            </div>

            <aside class="customize-card customize-side">
                <h2>Why this works better</h2>
                <ul>
                    <li>Built for Egypt travel specifics: Nile, heritage sites, deserts, and Red Sea.</li>
                    <li>Less friction: most fields are optional to improve completion rate.</li>
                    <li>Clear contact fallback: email or phone is enough to start planning.</li>
                    <li>The admin team receives structured requests for faster follow-up.</li>
                </ul>
            </aside>
        </div>
    </section>
@endsection
