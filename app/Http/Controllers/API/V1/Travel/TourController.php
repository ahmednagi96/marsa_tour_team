<?php

namespace App\Http\Controllers\API\V1\Travel;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\API\Travel\CheckDateRequest;
use App\Http\Requests\API\Travel\TourRequest;
use App\Http\Resources\Travel\TourAvailabilityResource;
use App\Http\Resources\Travel\TourListResource;
use App\Http\Resources\Travel\TourResource;
use App\Models\Tour;
use App\Services\Travel\TourService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TourController extends BaseController
{
    use ApiResponse;
    public function __construct(protected TourService $tourService) {}

    public function index(TourRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $tours = $this->tourService->getCachedTours($filters);
        $data = TourListResource::collection($tours)->response()->getData(true);
        return $this->success($data, __('messages.tours_retrieved'));
    }

    /**
     * @param  Tour $tour
     *  @return JsonResponse */
    public function show(Tour $tour): JsonResponse
    {
        $trip = $this->tourService->getCachedTourById($tour);
        $data = new TourResource($trip);
        return $this->success($data, __('messages.tour_retrieved'));
    }

    public function checkDate(Tour $tour, CheckDateRequest $request)
    {
        $validated = $request->validated();

        $result = $this->tourService
            ->getCachedTourAvailabilitiesById($tour, $validated);

        if (!$result) {
            return $this->success([], __('messages.tour_availability_retrieved'));
        }

      return $this->success([
        'availability' => new TourAvailabilityResource($result['data']),
        'type'         => $result['type'],
    ], __('messages.tour_availability_retrieved'));
        }
}
