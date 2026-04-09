<?php

namespace App\Services\Travel;

use App\Models\Trip;
use App\Traits\CacheableService;

class TripService
{
    use CacheableService;

    public function getCachedTrips(array $validatedData)
    {
        
        ksort($validatedData);
        $filterHash = md5(json_encode($validatedData));
        $cacheKey = "list_" . $filterHash;
    
        return $this->rememberWithTags('trips', $cacheKey, function () use ($validatedData){
            return Trip::query()
                ->with(['translations'])
                ->filter($validatedData['search'] ?? null)      
                ->trend($validatedData['trending'] ?? null)
                ->latest()
                ->paginate($validatedData['per_page'] ?? 15);
        });
    }
    public function getCachedTripById(int $id)
    {
        
        $cacheKey = "trip_show_{$id}" ;
    
        return $this->rememberWithTags('trips', $cacheKey, function ()use ($id){
            return Trip::query()
                ->with(['translations','tours'])
                ->findOrFail($id);
        });
    }
}