<?php

namespace App\Actions;

use App\DTOs\TourBookingRequestDto;
use App\DTOs\UserDto;
use App\Http\Resources\Payment\PaymentResource;
use App\Models\Booking;
use App\Services\Booking\BookingService;
use App\Warehouses\Travel\TourAvailabiltyManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Cache;

class BookingTour
{

    public function __construct(
        protected BookingService $bookingService,
        protected TourAvailabiltyManager $tourAvailabiltyManager,
        protected ChargePaymentAction $chargePayment,
        protected CreatePendingPayment $createPendingPayment,
        protected DatabaseManager $databaseManager
        )
    {
        //
    }
  #  public function handle(
   #     TourBookingRequestDto $tourBookingRequestDto,
    #    UserDto $userDto,
    #):PaymentResource {
     #   $lockKey = "booking_lock_availability_{$tourBookingRequestDto->tourAvailability->id}";
     #   return Cache::lock($lockKey, 10)->block(5, function () use ($tourBookingRequestDto, $userDto) {
      #        $this->bookingService->validateAvailability($tourBookingRequestDto);
       #       $booking = Booking::createFromDto($tourBookingRequestDto, $userDto->id);
        #      $this->tourAvailabiltyManager->reserveSeats($tourBookingRequestDto->tourAvailability->id,$tourBookingRequestDto->adultsCount);
         #     $reponseCharge=$this->chargePayment->handle($booking->load('tour','user'));
          #    $result=$this->createPendingPayment->handle( response:$reponseCharge,bookingId: $booking->id,provider:config('payment.provider'));
           #    return $result;
       # });

    #}
       public function handle(
       TourBookingRequestDto $tourBookingRequestDto,
        UserDto $userDto,
    ):PaymentResource {
        $lockKey = "booking_lock_availability_{$tourBookingRequestDto->tourAvailability->id}";
            return Cache::lock($lockKey, 10)->block(5, function () use ($tourBookingRequestDto, $userDto) {
               return $this->databaseManager->transaction(function () use ($tourBookingRequestDto,$userDto){
                $booking = Booking::createFromDto($tourBookingRequestDto, $userDto->id);
                $this->tourAvailabiltyManager->reserveSeats($tourBookingRequestDto->tourAvailability->id,$tourBookingRequestDto->adultsCount);
                return $booking;
               });            
            })->then(function ($booking) {
                    $reponseCharge = $this->chargePayment->handle($booking->load('tour', 'user'));
                    $result=$this->createPendingPayment->handle( response:$reponseCharge,bookingId: $booking->id,provider:config('payment.provider'));
                    return $result;
            });  
    }
}
