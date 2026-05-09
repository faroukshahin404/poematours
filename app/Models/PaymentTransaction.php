<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'payment_key',
    'stripe_session_id',
    'currency',
    'total_amount_minor',
    'paid_amount_minor',
    'charge_amount_minor',
    'status',
    'payment_link',
    'client_info',
    'gateway_payload',
])]
class PaymentTransaction extends Model
{
    public function scopeForBookingId(Builder $query, int $bookingId): Builder
    {
        return $query->where('gateway_payload->metadata->booking_id', (string) $bookingId);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'client_info' => 'array',
            'gateway_payload' => 'array',
        ];
    }
}
