<?php

namespace App\Models;

use App\Models\Concerns\HasJsonLocalizedName;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'package_label_group_id', 'created_by', 'updated_by'])]
class PackageLabel extends Model
{
    use HasJsonLocalizedName;

    /**
     * Build a unique slug within a label group.
     */
    public static function generateUniqueSlug(string $base, int $groupId, ?int $exceptId = null): string
    {
        $slug = trim(Str::slug($base));
        if ($slug === '') {
            $slug = 'label';
        }

        $candidate = $slug;
        $suffix = 2;
        while (static::query()
            ->where('package_label_group_id', $groupId)
            ->when($exceptId !== null, fn ($q) => $q->where('id', '!=', $exceptId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $slug.'-'.$suffix;
            $suffix++;
        }

        return $candidate;
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(PackageLabelGroup::class, 'package_label_group_id');
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
