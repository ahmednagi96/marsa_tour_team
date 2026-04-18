<?php

namespace App\Actions;

use App\DTOs\TourBookingRequestDto;
use App\DTOs\UserDto;
use App\Http\Resources\Booking\BookingResource;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use App\Warehouses\Travel\TourAvailabiltyManager;
use Illuminate\Support\Facades\Cache;

class BookingTour
{

    public function __construct(
        private BookingService $bookingService,
        private TourAvailabiltyManager $tourAvailabiltyManager
        )
    {
        //
    }
    public function handle(
        TourBookingRequestDto $tourBookingRequestDto,
        UserDto $userDto,
    ) {
        $lockKey = "booking_lock_availability_{$tourBookingRequestDto->tourAvailability->id}";
        return Cache::lock($lockKey, 10)->block(5, function () use ($tourBookingRequestDto, $userDto) {
              $this->bookingService->validateAvailability($tourBookingRequestDto);
              $booking = Booking::createFromDto($tourBookingRequestDto, $userDto->id);
              $this->tourAvailabiltyManager->reserveSeats($tourBookingRequestDto->tourAvailability->id,$tourBookingRequestDto->adultsCount);
              return new BookingResource($booking->load("tour"));
        });
    }
}
