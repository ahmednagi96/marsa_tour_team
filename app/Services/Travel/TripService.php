<?php

namespace App\Services\Travel;

use App\Models\Tour;
use App\Models\Trip;
use App\Traits\CacheableService;

class TripService
{
    use CacheableService;

    public function getCachedTrips(array $validatedData)
    {
        
        ksort($validatedData);
        $filterHash = md5(json_encode($validatedData));
        $cacheKey = "list_trips_" . $filterHash;
    
        return $this->rememberWithTags('trips', $cacheKey, function () use ($validatedData){
            return Trip::query()
                ->with(['translations'])
                ->filter($validatedData['search'] ?? null)      
                ->trend($validatedData['trending'] ?? null)
                ->latest()
                ->paginate($validatedData['per_page'] ?? 15);
        });
    }
    public function getCachedTripById(Trip $trip)
    {
        
        $cacheKey = "trip_show_{$trip->id}" ;
        return $this->rememberWithTags('trips', $cacheKey, function ()use ($trip){
            return $trip->load(['translations','tours','tours.trip']);
        });
    }
    public function getCachedTripToursById(int $id, array $validatedData)
    {
        ksort($validatedData);
        $filterHash = md5(json_encode($validatedData));
        $cacheKey = "trip_show_{$id}_tours_{$filterHash}" ;
        return $this->rememberWithTags('trips', $cacheKey, function () use ($id,$validatedData){
            return Tour::query()
                    ->where("trip_id",$id)
                    ->with(['translations','trip'])
                    ->filter($validatedData['search'] ?? null)      
                    ->active($validatedData['active'] ?? null)
                    ->favourite($validatedData['favourite'] ?? null)
                    ->discountTours($validatedData['discounts'] ?? null)
                    ->latest()
                    ->paginate($validatedData['per_page'] ?? 15);
        });
    }

    public function getCachedTripTourById(Trip $trip,Tour $tour)
    {
        $cacheKey = "trip_show_{$trip->id}_tour_{$tour->id}" ;
    
        return $this->rememberWithTags('trips', $cacheKey, function () use($tour){
            return $tour->load(['translations', 'trip']);
        });
    }
}