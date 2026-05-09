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
    'body',
    'show_in_footer',
    'footer_label',
    'footer_sort_order',
])]
class Page extends Model
{
    /**
     * @var array<string, string>
     */
    protected $casts = [
        'meta_keywords' => 'array',
        'og_tags' => 'array',
        'show_in_footer' => 'boolean',
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
