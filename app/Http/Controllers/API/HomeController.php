<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $homeService;
    public function __construct(HomeService $homeService){
        $this->homeService=$homeService;
    }

    public function getSomeTrips(){
        try{
            return sendResponse(200,'some Trips Retrieved Successfully ',$this->homeService->getSomeTrips());
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }
    public function getAllTrips(){
        try{
            return sendResponse(200,'All Trips Retrieved Successfully ',$this->homeService->getAllTrips());
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }
    public function allOffers(){
        try{
            return sendResponse(200,'All Offers Retrieved Successfully ',$this->homeService->getAllOffers());
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }
    public function getFavouriteTours(){
        try{
            return sendResponse(200,'Favourite Tours Retrieved Successfully ',$this->homeService->getFavouriteTours());
        }catch(\Exception $ex){

            return sendResponse(500,'internal server error !',null);
        }
    }
    public function getAllTours(){
        try{
            return sendResponse(200,'All Tours Retrieved Successfully ',$this->homeService->getAllTours());
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }

    public function banners(){
        try{
            return sendResponse(200,'All Banners Retrieved Successfully ',$this->homeService->getBanners());
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }

    public function limitedOffers(){
        try{
            return sendResponse(200,'Limited Offers Retrieved Successfully ',$this->homeService->getLimitedOffers());
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }
}
