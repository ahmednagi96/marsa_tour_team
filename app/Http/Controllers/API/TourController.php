<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function getTour(Tour $tour){
        try{
            return sendResponse(200,'Tour Retrieved Successfully ',new TourResource($tour));
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }
    public function toggleAuthFavourite(Tour $tour){
        try{
           $tour->userFavourite()->toggle(auth('sanctum')->id());
           return sendResponse(200,'Auth toggle favourite successfully ',['is_favourite'=>$tour->checkUserFavourite()]);
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }

}
