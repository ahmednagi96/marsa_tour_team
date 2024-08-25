<?php

namespace App\Services;

use App\Http\Resources\TourResource;
use App\Models\Tour;

class TripService
{
    public function getAllToursForEachTrip($tourId){
        $tours=Tour::where([
            ['is_active',1],['trip_id',$tourId]
        ])->orderBy('created_at','DESC')->get();
         if($tours->isNotEmpty()){
            return $tours->map(function($tour){
                return $tour->getHomeData();
            });
         }
         return [];
    }

    public function getAllCitiesForTours(){
        $cities=Tour::where('is_active',1)->get()->pluck('city')->toArray();
        if(count($cities)>0){
            return $cities;
        }
        return [];
    }

    public function searchToursByName($request){
        $query = Tour::where('is_active',1)
            ->whereTranslationLike('name',"%{$request->name}%")->get();
        if($query->isNotEmpty()){
            return $query->map(function($tour){
                return $tour->getHomeData();
            });
        }
        return [];
    }
    public function filterToursByCity($request){
        $query = Tour::where('is_active',1)
            ->whereTranslation('city',$request->city)->get();
        if($query->isNotEmpty()){
            return $query->map(function($tour){
                return $tour->getHomeData();
            });
        }
        return [];
    }

}
