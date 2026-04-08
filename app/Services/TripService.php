<?php

namespace App\Services;

use App\Models\Trip;
use App\Traits\CacheableService;

class TripService
{
    use CacheableService;

    public function getCachedTrips()
    {
        $filters = request()->only(['search', 'trending', 'per_page', 'page']);
        
        $filterHash = md5(json_encode($filters));
        $cacheKey = "list_" . $filterHash;
    
        return $this->rememberWithTags('trips', $cacheKey, function () use ($filters){
            return Trip::query()
                ->with(['translations'])
                ->filter($filters['search'] ?? null)      
                ->trend($filters['trending'] ?? false)
                ->latest()
                ->paginate($filters['per_page'] ?? 15);
        });
    }
}