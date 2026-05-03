<?php

namespace App\Models;

use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'hotel_id', 'capacity', 'area', 'base_price', 'single_supplement', 'created_by', 'updated_by'])]
class HotelRoom extends Model
{
    use HasJsonLocalizedName;

    /**
     * Build a unique slug within a hotel.
     */
    public static function generateUniqueSlug(string $base, int $hotelId, ?int $exceptId = null): string
    {
        $slug = trim(Str::slug($base));
        if ($slug === '') {
            $slug = 'room';
        }

        $candidate = $slug;
        $suffix = 2;
        while (static::query()
            ->where('hotel_id', $hotelId)
            ->when($exceptId !== null, fn ($q) => $q->where('id', '!=', $exceptId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model', 'model_type', 'model_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
