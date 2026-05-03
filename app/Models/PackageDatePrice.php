<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['package_id', 'from_date', 'to_date', 'available_seats', 'price', 'offer'])]
class PackageDatePrice extends Model
{
    public function package(): BelongsTo
    {
        return $this->belongsTo(TravelPackage::class, 'package_id');
    }

    public function accommodations(): HasMany
    {
        return $this->hasMany(PackageDatePriceAccommodation::class, 'package_date_price_id');
    }

    public function finalPrice(): float
    {
        $price = (float) $this->price;
        $offer = (float) ($this->offer ?? 0);

        return max($price - $offer, 0);
    }
}
