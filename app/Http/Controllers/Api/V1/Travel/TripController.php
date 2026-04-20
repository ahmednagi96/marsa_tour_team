<?php

namespace App\Http\Controllers\Api\V1\Travel;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\API\Travel\TourRequest;
use App\Http\Requests\API\Travel\TripRequest;
use App\Http\Resources\Travel\TourListResource;
use App\Http\Resources\Travel\TripResource;
use App\Models\Tour;
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
     * @param  Trip $trip
     *  @return JsonResponse */
    public function show(Trip $trip): JsonResponse
    {
        $trip = $this->tripService->getCachedTripById($trip);
        $data = new TripResource($trip);
        return $this->success($data, __('messages.trip_retrieved'));
    }
    /**
     * @param  TourRequest $request
     *  @return JsonResponse */
    public function tours(Trip $trip, TourRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $tripTours = $this->tripService->getCachedTripToursById($trip->id, $filters);
        $data = TourListResource::collection($tripTours)->response()->getData(true);
        return $this->success($data, __('messages.trip_tours_retrieved'));
    }


    /** 
     * @param Trip $trip
     * @param Tour $tour
     * @return  JsonResponse */
    public function tripTour(Trip $trip, Tour $tour): JsonResponse
    {
        $tour = $this->tripService->getCachedTripTourById($trip, $tour);
        $data = new TripResource($tour);
        return $this->success($data, __('messages.trip_tour_retrieved'));
    }
}
