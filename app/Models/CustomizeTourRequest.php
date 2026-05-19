<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'package_id',
    'full_name',
    'email',
    'phone',
    'adults',
    'children',
    'arrival_date',
    'departure_date',
    'duration_days',
    'destinations',
    'interests',
    'accommodation_style',
    'budget_range',
    'need_guide',
    'need_transportation',
    'preferred_contact_method',
    'notes',
    'status',
    'admin_notes',
])]
class CustomizeTourRequest extends Model
{
    public function package(): BelongsTo
    {
        return $this->belongsTo(TravelPackage::class, 'package_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'arrival_date' => 'date',
            'departure_date' => 'date',
            'interests' => 'array',
            'need_guide' => 'boolean',
            'need_transportation' => 'boolean',
        ];
    }

    protected function contactSummary(): Attribute
    {
        return Attribute::get(function (): string {
            $parts = array_filter([
                $this->email,
                $this->phone,
            ]);

            return $parts !== [] ? implode(' | ', $parts) : 'No contact details';
        });
    }
}
