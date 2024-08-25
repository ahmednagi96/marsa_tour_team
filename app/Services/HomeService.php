<?php

namespace App\Services;

use App\Http\Resources\OfferResource;
use App\Http\Resources\TourResource;
use App\Http\Resources\TripResource;
use App\Models\Offer;
use App\Models\Tour;
use App\Models\Trip;
class HomeService{

    public function getSomeTrips(){
        $trips=Trip::orderBy('created_at','DESC')->limit(4)->get();
         if($trips->isNotEmpty()){
            return TripResource::collection($trips);
         }
         return [];
    }
    public function getAllTrips(){
        $trips=Trip::orderBy('created_at','DESC')->get();
         if($trips->isNotEmpty()){
            return TripResource::collection($trips);
         }
         return [];
    }
    public function getFavouriteTours(){
        $tours=Tour::where('is_active',1)->where('is_favourite',1)->orderBy('created_at','DESC')->limit(4)->get();
         if($tours->isNotEmpty()){
            return $tours->map(function($tour){
                return $tour->getHomeData();
            });
         }
         return [];
    }
    public function getAllTours(){
        $tours=Tour::where('is_active',1)->orderBy('created_at','DESC')->limit(4)->get();
         if($tours->isNotEmpty()){
            return TourResource::collection($tours);
         }
         return [];
    }

    public function getBanners(){
        $offers=Offer::orderBy('created_at','DESC')->limit(4)->get();
        if($offers->isNotEmpty()){
           return OfferResource::collection($offers);
        }
        return [];
    }
    public function getLimitedOffers(){
        $offers=Offer::orderBy('special_price_end','ASC')->limit(4)->get();
        if($offers->isNotEmpty()){
           return OfferResource::collection($offers);
        }
        return [];
    }
    public function getAllOffers(){
        $offers=Offer::all();
        if($offers->isNotEmpty()){
           return OfferResource::collection($offers);
        }
        return [];
    }

}
