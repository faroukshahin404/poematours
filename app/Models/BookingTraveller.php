<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'booking_id',
    'sort_order',
    'first_name_on_passport',
    'middle_name_on_passport',
    'last_name_on_passport',
    'gender',
    'birthdate',
    'passport_country',
    'passport_number',
    'passport_expiration_date',
    'country_of_birth',
    'father_first_name',
    'passport_photo_path',
])]
class BookingTraveller extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'passport_expiration_date' => 'date',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
