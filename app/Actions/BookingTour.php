<?php

namespace App\Actions;

use App\DTOs\TourBookingRequestDto;
use App\DTOs\UserDto;
use App\Services\Booking\BookingService;

class BookingTour
{

    public function __construct(private BookingService $bookingService)
    {
        //
    }
    public function handle(
        TourBookingRequestDto $tourBookingRequestDto,
        UserDto $userDto,
    ) {
        # $booking = $this->bookingService->createBooking($request->validated(), $request->user());

        #   $responseData = [
        #      'booking_id'  => $booking->id,
        #     'total_price' => $booking->total_price,
        #    'status'      => $booking->status,
        # ];
    }
}
