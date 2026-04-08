<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\TripResource;
use App\Services\TripService;
use Illuminate\Http\JsonResponse;

class TripController extends BaseController
{
    public function __construct(protected TripService $tripService) {}

    public function index():JsonResponse
    {
        $trips = $this->tripService->getCachedTrips();
        $data = TripResource::collection($trips)->response()->getData(true);
        return $this->success($data, __('messages.trips_retrieved'));
    }
   
}
