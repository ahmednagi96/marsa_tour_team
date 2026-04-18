<?php

namespace App\Exceptions\Booking;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TourNotActiveException extends Exception
{
    use ApiResponse;
    public function render(Request $request): JsonResponse
    {
        return $this->error( __('messages.tour_not_available'),400);
       
    }
}
