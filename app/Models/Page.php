<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'og_tags',
])]
class Page extends Model
{
    /**
     * @var array<string, string>
     */
    protected $casts = [
        'meta_keywords' => 'array',
        'og_tags' => 'array',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class, 'page_id');
    }

    public function activeSections(): HasMany
    {
        return $this->sections()->active()->ordered();
    }
}
