<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\TripResource;
use App\Services\TripService;

class TripController extends BaseController
{
    public function __construct(protected TripService $tripService) {}

    public function index()
    {
        $trips = $this->tripService->getCachedTrips(15);
        $data = TripResource::collection($trips)->response()->getData(true);
        return $this->success($data, __('messages.trips_retrieved'));
    }
}
