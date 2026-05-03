<?php

namespace App\Models;

use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'created_by', 'updated_by'])]
class PackageLabelGroup extends Model
{
    use HasJsonLocalizedName;

    /**
     * Build a unique slug.
     */
    public static function generateUniqueSlug(string $base, ?int $exceptId = null): string
    {
        $slug = trim(Str::slug($base));
        if ($slug === '') {
            $slug = 'label-group';
        }

        $candidate = $slug;
        $suffix = 2;
        while (static::query()
            ->when($exceptId !== null, fn ($q) => $q->where('id', '!=', $exceptId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }

    public function labels(): HasMany
    {
        return $this->hasMany(PackageLabel::class, 'package_label_group_id');
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
