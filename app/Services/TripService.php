<?php

namespace App\Services;

use App\Models\Trip;
use App\Traits\CacheableService;

class TripService
{
    use CacheableService;

    public function getCachedTrips()
    {
        return $this->rememberWithTags('trips', 'list', function () {
            return Trip::with(['translations'])->paginate(request('per_page', 15));
        });
    }
}