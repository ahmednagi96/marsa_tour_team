<?php

namespace App\Http\Controllers\API\V1\Travel;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\Travel\TourListResource;
use App\Services\Travel\TourService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TourController extends BaseController
{
    use ApiResponse;
    public function __construct(protected TourService $tourService) {}

    public function index():JsonResponse
    {
        $tours = $this->tourService->getCachedTours();
        $data = TourListResource::collection($tours)->response()->getData(true);
        return $this->success($data, __('messages.tours_retrieved'));
    }
   
}
