<?php

namespace App\Http\Requests\FronEnd;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $cleanTravellerEmails = collect((array) $this->input('traveler_emails', []))
            ->map(fn (mixed $value): string => strtolower(trim((string) $value)))
            ->filter()
            ->values()
            ->all();

        $this->merge([
            'email' => $this->filled('email') ? strtolower(trim((string) $this->input('email'))) : null,
            'first_name' => trim((string) $this->input('first_name')),
            'last_name' => trim((string) $this->input('last_name')),
            'traveler_emails' => $cleanTravellerEmails,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'package_id' => ['nullable', 'integer', 'exists:packages,id'],
            'package_date_price_id' => ['nullable', 'integer', 'exists:package_date_prices,id'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'booking_source' => ['nullable', 'string', Rule::in(['general', 'package'])],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'traveler_emails' => ['nullable', 'array'],
            'traveler_emails.*' => ['nullable', 'email:rfc,dns', 'max:255'],
            'contact_phone_number' => ['nullable', 'string', 'max:50'],
            'itinerary_cover_name' => ['nullable', 'string', 'max:255'],
            'mailing_street' => ['nullable', 'string', 'max:255'],
            'mailing_street_line_2' => ['nullable', 'string', 'max:255'],
            'mailing_city' => ['nullable', 'string', 'max:255'],
            'mailing_state' => ['nullable', 'string', 'max:255'],
            'mailing_zip_code' => ['nullable', 'string', 'max:50'],
            'mailing_country' => ['nullable', 'string', 'max:255'],
            'mobility_concerns' => ['nullable', 'array'],
            'mobility_concerns.*' => ['string', Rule::in([
                'stairs',
                'walking_pace',
                'walking_distances',
                'hills_or_uneven_paths',
                'wheelchair_mobility_scooter_stroller_access',
            ])],
            'dietary_restrictions' => ['nullable', 'string', 'max:5000'],
            'other_needs_or_requests' => ['nullable', 'string', 'max:5000'],
            'dynamic_answers' => ['nullable', 'array'],
            'travellers_count' => ['required', 'integer', 'min:0', 'max:50'],
            'travellers' => ['nullable', 'array'],
            'travellers.*.sort_order' => ['nullable', 'integer', 'min:1'],
            'travellers.*.first_name_on_passport' => ['nullable', 'string', 'max:255'],
            'travellers.*.middle_name_on_passport' => ['nullable', 'string', 'max:255'],
            'travellers.*.last_name_on_passport' => ['nullable', 'string', 'max:255'],
            'travellers.*.gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'travellers.*.birthdate' => ['nullable', 'date'],
            'travellers.*.passport_country' => ['nullable', 'string', 'max:255'],
            'travellers.*.passport_number' => ['nullable', 'string', 'max:255'],
            'travellers.*.passport_expiration_date' => ['nullable', 'date'],
            'travellers.*.country_of_birth' => ['nullable', 'string', 'max:255'],
            'travellers.*.father_first_name' => ['nullable', 'string', 'max:255'],
            'travellers.*.passport_photo' => ['nullable', 'image', 'max:5120'],
            'flight_option' => ['nullable', 'string', Rule::in(['enter_now', 'send_later', 'no_transportation', 'other'])],
            'arrival_flight_date' => ['nullable', 'date', 'required_if:flight_option,enter_now'],
            'arrival_flight_time' => ['nullable', 'date_format:H:i', 'required_if:flight_option,enter_now'],
            'arrival_flight_airline' => ['nullable', 'string', 'max:255', 'required_if:flight_option,enter_now'],
            'arrival_flight_number' => ['nullable', 'string', 'max:255', 'required_if:flight_option,enter_now'],
            'return_flight_date' => ['nullable', 'date', 'required_if:flight_option,enter_now'],
            'return_flight_departure_time' => ['nullable', 'date_format:H:i', 'required_if:flight_option,enter_now'],
            'return_flight_airline' => ['nullable', 'string', 'max:255', 'required_if:flight_option,enter_now'],
            'return_flight_number' => ['nullable', 'string', 'max:255', 'required_if:flight_option,enter_now'],
            'flight_other_text' => ['nullable', 'string', 'max:2000', 'required_if:flight_option,other'],
            'booking_status' => ['nullable', 'string', Rule::in(['pending', 'contacted', 'confirmed', 'cancelled'])],
            'payment_status' => ['nullable', 'string', Rule::in(['not_paid', 'partially_paid', 'fully_paid'])],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function bookingPayload(): array
    {
        $validated = $this->validated();

        return [
            'package_id' => isset($validated['package_id']) ? (int) $validated['package_id'] : null,
            'package_date_price_id' => isset($validated['package_date_price_id']) ? (int) $validated['package_date_price_id'] : null,
            'unit_price' => isset($validated['unit_price']) ? (float) $validated['unit_price'] : null,
            'booking_source' => $validated['booking_source'] ?? 'general',
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'] ?? null,
            'traveler_emails' => $validated['traveler_emails'] ?? [],
            'contact_phone_number' => $validated['contact_phone_number'] ?? null,
            'itinerary_cover_name' => $validated['itinerary_cover_name'] ?? null,
            'mailing_street' => $validated['mailing_street'] ?? null,
            'mailing_street_line_2' => $validated['mailing_street_line_2'] ?? null,
            'mailing_city' => $validated['mailing_city'] ?? null,
            'mailing_state' => $validated['mailing_state'] ?? null,
            'mailing_zip_code' => $validated['mailing_zip_code'] ?? null,
            'mailing_country' => $validated['mailing_country'] ?? null,
            'mobility_concerns' => $validated['mobility_concerns'] ?? [],
            'dietary_restrictions' => $validated['dietary_restrictions'] ?? null,
            'other_needs_or_requests' => $validated['other_needs_or_requests'] ?? null,
            'dynamic_answers' => $validated['dynamic_answers'] ?? [],
            'travellers_count' => (int) ($validated['travellers_count'] ?? 0),
            'flight_option' => $validated['flight_option'] ?? null,
            'arrival_flight_date' => $validated['arrival_flight_date'] ?? null,
            'arrival_flight_time' => $validated['arrival_flight_time'] ?? null,
            'arrival_flight_airline' => $validated['arrival_flight_airline'] ?? null,
            'arrival_flight_number' => $validated['arrival_flight_number'] ?? null,
            'return_flight_date' => $validated['return_flight_date'] ?? null,
            'return_flight_departure_time' => $validated['return_flight_departure_time'] ?? null,
            'return_flight_airline' => $validated['return_flight_airline'] ?? null,
            'return_flight_number' => $validated['return_flight_number'] ?? null,
            'flight_other_text' => $validated['flight_other_text'] ?? null,
            'booking_status' => $validated['booking_status'] ?? 'pending',
            'payment_status' => $validated['payment_status'] ?? 'not_paid',
            'paid_amount' => isset($validated['paid_amount']) ? (float) $validated['paid_amount'] : 0,
            'total_amount' => isset($validated['total_amount']) ? (float) $validated['total_amount'] : null,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function travellersPayload(): array
    {
        return collect((array) ($this->validated()['travellers'] ?? []))
            ->map(function (mixed $traveller, int $index): array {
                $data = is_array($traveller) ? $traveller : [];

                return [
                    'sort_order' => (int) ($data['sort_order'] ?? ($index + 1)),
                    'first_name_on_passport' => $data['first_name_on_passport'] ?? null,
                    'middle_name_on_passport' => $data['middle_name_on_passport'] ?? null,
                    'last_name_on_passport' => $data['last_name_on_passport'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'birthdate' => $data['birthdate'] ?? null,
                    'passport_country' => $data['passport_country'] ?? null,
                    'passport_number' => $data['passport_number'] ?? null,
                    'passport_expiration_date' => $data['passport_expiration_date'] ?? null,
                    'country_of_birth' => $data['country_of_birth'] ?? null,
                    'father_first_name' => $data['father_first_name'] ?? null,
                ];
            })
            ->values()
            ->all();
    }
}
