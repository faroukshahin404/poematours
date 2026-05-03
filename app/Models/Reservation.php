<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'uuid',
    'full_name',
    'email',
    'phone',
    'country',
    'preferred_contact_method',
    'adults',
    'children',
    'arrival_date',
    'departure_date',
    'duration_days',
    'destinations',
    'tour_style',
    'hotel_category',
    'need_transfers',
    'need_domestic_flights',
    'selected_addons',
    'addons_breakdown',
    'addons_total',
    'base_total',
    'estimated_total',
    'deposit_percentage',
    'deposit_amount',
    'currency',
    'payment_status',
    'status',
    'stripe_payment_intent_id',
    'paid_at',
    'notes',
])]
class Reservation extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'arrival_date' => 'date',
            'departure_date' => 'date',
            'paid_at' => 'datetime',
            'selected_addons' => 'array',
            'addons_breakdown' => 'array',
            'need_transfers' => 'boolean',
            'need_domestic_flights' => 'boolean',
        ];
    }
}
