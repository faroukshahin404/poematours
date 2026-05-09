<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'package_id',
    'package_date_price_id',
    'unit_price',
    'booking_source',
    'first_name',
    'last_name',
    'email',
    'traveler_emails',
    'contact_phone_number',
    'itinerary_cover_name',
    'mailing_street',
    'mailing_street_line_2',
    'mailing_city',
    'mailing_state',
    'mailing_zip_code',
    'mailing_country',
    'mobility_concerns',
    'dietary_restrictions',
    'other_needs_or_requests',
    'dynamic_answers',
    'travellers_count',
    'flight_option',
    'arrival_flight_date',
    'arrival_flight_time',
    'arrival_flight_airline',
    'arrival_flight_number',
    'return_flight_date',
    'return_flight_departure_time',
    'return_flight_airline',
    'return_flight_number',
    'flight_other_text',
    'booking_status',
    'payment_status',
    'paid_amount',
    'total_amount',
])]
class Booking extends Model
{
    /**
     * @var array<int, string>
     */
    public const BOOKING_STATUSES = ['pending', 'contacted', 'confirmed', 'cancelled'];

    /**
     * @var array<int, string>
     */
    public const PAYMENT_STATUSES = ['not_paid', 'partially_paid', 'fully_paid'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'traveler_emails' => 'array',
            'mobility_concerns' => 'array',
            'dynamic_answers' => 'array',
            'unit_price' => 'decimal:2',
            'arrival_flight_date' => 'date',
            'return_flight_date' => 'date',
            'paid_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function travellers(): HasMany
    {
        return $this->hasMany(BookingTraveller::class, 'booking_id');
    }
}
