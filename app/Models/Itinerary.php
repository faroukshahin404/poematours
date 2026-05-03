<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'package_id',
    'title',
    'description',
    'breakfast',
    'lunch',
    'dinner',
    'snacks',
    'destination_id',
    'hotel_id',
    'boat_id',
    'sort_order',
])]
class Itinerary extends Model
{
    public function package(): BelongsTo
    {
        return $this->belongsTo(TravelPackage::class, 'package_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function boat(): BelongsTo
    {
        return $this->belongsTo(Boat::class, 'boat_id');
    }
}
