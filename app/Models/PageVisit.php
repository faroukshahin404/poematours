<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'route_name',
    'path',
    'route_parameters',
    'package_slug',
    'country_code',
    'country_name',
    'ip_address',
    'visited_at',
])]
class PageVisit extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'route_parameters' => 'array',
            'visited_at' => 'datetime',
        ];
    }
}
