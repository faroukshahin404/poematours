<?php

namespace App\Models;

use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'icon', 'created_by', 'updated_by'])]
class PackageInclusion extends Model
{
    use HasJsonLocalizedName;

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(
            TravelPackage::class,
            'package_package_inclusion',
            'package_inclusion_id',
            'package_id'
        );
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
