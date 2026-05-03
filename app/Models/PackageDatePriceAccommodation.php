<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['package_date_price_id', 'hotel_id', 'room_id'])]
class PackageDatePriceAccommodation extends Model
{
    public $timestamps = false;

    public function datePrice(): BelongsTo
    {
        return $this->belongsTo(PackageDatePrice::class, 'package_date_price_id');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(HotelRoom::class, 'room_id');
    }
}
