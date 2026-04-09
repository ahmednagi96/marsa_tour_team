<?php

namespace App\Services\Travel;

use App\Models\Tour;
use App\Traits\CacheableService;

class TourService
{
    use CacheableService;

    public function getCachedTours()
    {
        $filters = request()->only(['search', 'active', 'favourite',"discounts",'per_page', 'page']);
        
        $filterHash = md5(json_encode($filters));
        $cacheKey = "list_" . $filterHash;
    
        return $this->rememberWithTags('tours', $cacheKey, function () use ($filters){
            return Tour::query()
                ->with(['translations','trip'])
                ->filter($filters['search'] ?? null)      
                ->active($filters['active'] ?? false)
                ->favourite($filters['favourite'] ?? false)
                ->latest()
                ->paginate($filters['per_page'] ?? 15);
        });
    }
}