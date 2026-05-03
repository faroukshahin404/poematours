<?php

namespace App\Services\Frontend;

use App\Models\Destination;
use Illuminate\Support\Collection;

class DestinationViewService
{
    /**
     * @return Collection<int, Destination>
     */
    public function all(): Collection
    {
        return Destination::query()
            ->orderBy('slug')
            ->get(['id', 'name', 'slug', 'image']);
    }
}
