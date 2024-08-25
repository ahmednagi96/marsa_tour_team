<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Services\TripService;
use Illuminate\Http\Request;

class TripController extends Controller
{
    private $tripService;

    public function __construct(TripService $tripService){
        $this->tripService=$tripService;
    }
    public function getToursForEachTrip(Trip $trip){
        try{
            return sendResponse(200,'All Tours For This Trip Retrieved Successfully ',$this->tripService->getAllToursForEachTrip($trip->id));
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }
    public function getTourCities(){
        try{
            return sendResponse(200,'All Cities Retrieved Successfully ',$this->tripService->getAllCitiesForTours());
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }

    public function searchToursByName(Request $request){
        try{
            return sendResponse(200,'search By Name Retrieved Successfully ',$this->tripService->searchToursByName($request));
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }
    public function filterToursByCity(Request $request){
        try{
            return sendResponse(200,'Filter By City Retrieved Successfully ',$this->tripService->filterToursByCity($request));
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }


}
