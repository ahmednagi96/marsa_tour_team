<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function toggleAuthFavourite(Offer $offer){
        try{
           $offer->userFavourite()->toggle(auth('sanctum')->id());
           return sendResponse(200,'Auth toggle favourite successfully ',['is_favourite'=>$offer->checkUserFavourite()]);
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }
}
