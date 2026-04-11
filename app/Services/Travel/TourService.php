<?php

namespace App\Services\Travel;

use App\Models\Tour;
use App\Traits\CacheableService;

class TourService
{
    use CacheableService;

    public function getCachedTours(array $validatedData)
    {
        ksort($validatedData);
        $filterHash = md5(json_encode($validatedData));
        $cacheKey = "list_tours_" . $filterHash;
        return $this->rememberWithTags('tours', $cacheKey, function () use ($validatedData) {
            return Tour::query()
                ->with(['translations', 'trip'])
                ->filter($validatedData['search'] ?? null)
                ->active($validatedData['active'] ?? null)
                ->favourite($validatedData['favourite'] ?? null)
                ->discountTours($validatedData['discounts'] ?? null)
                ->latest()
                ->paginate($validatedData['per_page'] ?? 15);
        });
    }


    public function getCachedTourById(Tour $tour)
    {
        $cacheKey = "tour_show_{$tour->id}";
        return $this->rememberWithTags('trips', $cacheKey, function () use ($tour) {
            return $tour->load(['translations', 'trip']);
        });
    }
}
