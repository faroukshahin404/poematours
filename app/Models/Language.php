<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug', 'is_default', 'created_by', 'updated_by'])]
class Language extends Model
{
    /**
     * Slug of the default site language, or the first language, or a safe fallback.
     */
    public static function defaultSlug(): string
    {
        return static::query()->where('is_default', true)->value('slug')
            ?? static::query()->orderBy('id')->value('slug')
            ?? 'en';
    }

    /**
     * Build a unique slug from a human-readable name.
     */
    public static function generateUniqueSlug(string $name, ?int $exceptId = null): string
    {
        $base = trim(Str::slug($name));
        if ($base === '') {
            $base = 'language';
        }

        $slug = $base;
        $suffix = 2;
        while (static::query()
            ->when($exceptId !== null, fn ($q) => $q->where('id', '!=', $exceptId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
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
