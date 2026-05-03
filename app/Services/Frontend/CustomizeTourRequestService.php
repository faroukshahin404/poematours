<?php

namespace App\Services\Frontend;

use App\Models\CustomizeTourRequest;

class CustomizeTourRequestService
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function create(array $payload): CustomizeTourRequest
    {
        return CustomizeTourRequest::query()->create($payload);
    }
}
