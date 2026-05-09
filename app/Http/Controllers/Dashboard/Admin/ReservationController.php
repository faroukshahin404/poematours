<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Contracts\Repositories\Dashboard\Admin\ReservationRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\UpdateReservationStatusRequest;
use App\Models\Booking;
use App\Models\PaymentTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Admin/Reservations/Index', [
            'reservations' => $this->reservations->paginate(15)->through(function (Booking $booking): array {
                return [
                    'id' => $booking->id,
                    'guest_name' => trim($booking->first_name.' '.$booking->last_name),
                    'email' => $booking->email,
                    'contact_phone_number' => $booking->contact_phone_number,
                    'travellers_count' => $booking->travellers_count,
                    'booking_status' => $booking->booking_status,
                    'payment_status' => $booking->payment_status,
                    'paid_amount' => (float) ($booking->paid_amount ?? 0),
                    'total_amount' => $booking->total_amount !== null ? (float) $booking->total_amount : null,
                    'created_at' => $booking->created_at?->format('Y-m-d H:i'),
                ];
            }),
            'bookingStatuses' => Booking::BOOKING_STATUSES,
            'paymentStatuses' => Booking::PAYMENT_STATUSES,
        ]);
    }

    public function show(Booking $reservation): Response
    {
        $reservation = $this->reservations->findOrFail((int) $reservation->id);
        $paymentTransaction = PaymentTransaction::query()
            ->forBookingId((int) $reservation->id)
            ->latest('id')
            ->first();

        return Inertia::render('Dashboard/Admin/Reservations/Show', [
            'reservation' => [
                'id' => $reservation->id,
                'first_name' => $reservation->first_name,
                'last_name' => $reservation->last_name,
                'email' => $reservation->email,
                'traveler_emails' => $reservation->traveler_emails ?? [],
                'contact_phone_number' => $reservation->contact_phone_number,
                'itinerary_cover_name' => $reservation->itinerary_cover_name,
                'mailing_street' => $reservation->mailing_street,
                'mailing_street_line_2' => $reservation->mailing_street_line_2,
                'mailing_city' => $reservation->mailing_city,
                'mailing_state' => $reservation->mailing_state,
                'mailing_zip_code' => $reservation->mailing_zip_code,
                'mailing_country' => $reservation->mailing_country,
                'mobility_concerns' => $reservation->mobility_concerns ?? [],
                'dietary_restrictions' => $reservation->dietary_restrictions,
                'other_needs_or_requests' => $reservation->other_needs_or_requests,
                'dynamic_answers' => $reservation->dynamic_answers ?? [],
                'travellers_count' => $reservation->travellers_count,
                'flight_option' => $reservation->flight_option,
                'arrival_flight_date' => $reservation->arrival_flight_date?->format('Y-m-d'),
                'arrival_flight_time' => $reservation->arrival_flight_time,
                'arrival_flight_airline' => $reservation->arrival_flight_airline,
                'arrival_flight_number' => $reservation->arrival_flight_number,
                'return_flight_date' => $reservation->return_flight_date?->format('Y-m-d'),
                'return_flight_departure_time' => $reservation->return_flight_departure_time,
                'return_flight_airline' => $reservation->return_flight_airline,
                'return_flight_number' => $reservation->return_flight_number,
                'flight_other_text' => $reservation->flight_other_text,
                'booking_status' => $reservation->booking_status,
                'payment_status' => $reservation->payment_status,
                'paid_amount' => (float) ($reservation->paid_amount ?? 0),
                'total_amount' => $reservation->total_amount !== null ? (float) $reservation->total_amount : null,
                'payment_transaction' => $paymentTransaction ? [
                    'payment_key' => $paymentTransaction->payment_key,
                    'stripe_session_id' => $paymentTransaction->stripe_session_id,
                    'status' => $paymentTransaction->status,
                    'currency' => $paymentTransaction->currency,
                    'charge_amount' => round(((int) $paymentTransaction->charge_amount_minor) / 100, 2),
                    'payment_link' => $paymentTransaction->payment_link,
                    'session_status' => data_get($paymentTransaction->gateway_payload, 'session_status'),
                    'payment_status' => data_get($paymentTransaction->gateway_payload, 'payment_status'),
                    'event_type' => data_get($paymentTransaction->gateway_payload, 'event_type'),
                    'created_at' => $paymentTransaction->created_at?->format('Y-m-d H:i'),
                ] : null,
                'created_at' => $reservation->created_at?->format('Y-m-d H:i'),
                'travellers' => $reservation->travellers->map(fn ($traveller): array => [
                    'id' => $traveller->id,
                    'sort_order' => $traveller->sort_order,
                    'first_name_on_passport' => $traveller->first_name_on_passport,
                    'middle_name_on_passport' => $traveller->middle_name_on_passport,
                    'last_name_on_passport' => $traveller->last_name_on_passport,
                    'gender' => $traveller->gender,
                    'birthdate' => $traveller->birthdate?->format('Y-m-d'),
                    'passport_country' => $traveller->passport_country,
                    'passport_number' => $traveller->passport_number,
                    'passport_expiration_date' => $traveller->passport_expiration_date?->format('Y-m-d'),
                    'country_of_birth' => $traveller->country_of_birth,
                    'father_first_name' => $traveller->father_first_name,
                    'passport_photo_url' => $traveller->passport_photo_path ? asset('storage/'.$traveller->passport_photo_path) : null,
                ])->values()->all(),
            ],
            'bookingStatuses' => Booking::BOOKING_STATUSES,
            'paymentStatuses' => Booking::PAYMENT_STATUSES,
        ]);
    }

    public function updateStatus(
        UpdateReservationStatusRequest $request,
        Booking $reservation
    ): RedirectResponse {
        $validated = $request->validated();

        $this->reservations->updateStatus(
            $reservation,
            (string) $validated['booking_status'],
            (string) $validated['payment_status'],
            (float) ($validated['paid_amount'] ?? 0),
            isset($validated['total_amount']) ? (float) $validated['total_amount'] : null,
        );

        return redirect()
            ->route('admin.reservations.show', $reservation)
            ->with('status', __('Reservation status updated successfully.'));
    }

    public function receipt(Booking $reservation, Request $request)
    {
        $reservation = $this->reservations->findOrFail((int) $reservation->id);

        return response()->view('dashboard.admin.reservations.receipt', [
            'reservation' => $reservation,
            'print' => $request->boolean('print'),
        ]);
    }
}
