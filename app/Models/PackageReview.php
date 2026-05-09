<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageReview extends Model
{
    protected $table = 'package_reviews';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'package_id',
        'reviewer_name',
        'reviewer_address',
        'comment',
        'rate',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rate' => 'integer',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TravelPackage::class, 'package_id');
    }
}
