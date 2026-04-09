<?php

namespace App\Http\Controllers\API\V1\Travel;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\API\Travel\TripRequest;
use App\Http\Resources\Travel\TripResource;
use App\Models\Trip;
use App\Services\Travel\TripService;
use Illuminate\Http\JsonResponse;

class TripController extends BaseController
{
    public function __construct(protected TripService $tripService) {}


    
    /**
     * @param  TripRequest $request
     *  @return JsonResponse */
    public function index(TripRequest $request)
    {

        $filters = $request->validated();        
        $trips = $this->tripService->getCachedTrips($filters);
        $data = TripResource::collection($trips)->response()->getData(true);
        return $this->success($data, __('messages.trips_retrieved'));
    }
      /**
     * @param  TripRequest $request
     *  @return JsonResponse */
    public function show(Trip $trip):JsonResponse
    {
        $trip = $this->tripService->getCachedTripById($trip->id);
        $data = new TripResource($trip);
        return $this->success($data, __('messages.trip_retrieved'));
    }
   
}
