<?php

namespace App\Services\Travel;

use App\Models\Tour;
use App\Models\TourAvailability;
use App\Traits\CacheableService;
use Carbon\Carbon;

class TourService
{

    /**  CacheableService instanceof  App\Traits\CacheableService*/
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

    public function getCachedTourAvailabilitiesById(Tour $tour, array $validatedData)
    {
        ksort($validatedData);
        $filterHash = md5(json_encode($validatedData));
        $cacheKey = "tour_show_{$tour->id}_{$filterHash}";    
        return $this->rememberWithTags('tour_availabilties', $cacheKey, function () use ($tour, $validatedData) {
            return $this->checkTourAvailabilities($tour, $validatedData);
        });
    }
    
    protected function checkTourAvailabilities(Tour $tour, array $validatedData)
    {
        if (!$tour) return null;
    
        $date = Carbon::createFromFormat('d-m-Y', $validatedData['date'])->format('Y-m-d');
    
        // 1. Exact Match
        $availability = $tour->tourAvailability()
            ->active()
            ->whereDate('date', $date)
            ->whereColumn('booked', '<', 'capacity')
            ->first();
    
        if ($availability) {
            return [
                "type" => "exact",
                "data" => $availability
            ];
        }
    
        // 2. Suggestion
        $suggestion = $tour->tourAvailability()
            ->active(true) // تأكد أن السكوب لا يحتاج true إلا لو كنت معرفه كدا
            ->whereDate('date', '>', $date)
            ->whereColumn('booked', '<', 'capacity')
            ->orderBy('date', 'asc')
            ->first();
    
        if ($suggestion) {
            return [
                "type" => "suggestion",
                "data" => $suggestion
            ];
        }
    
        return null;
    }
}
