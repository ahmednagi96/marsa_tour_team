<?php

namespace App\Services\Booking;

use App\DTOs\TourAvailabilityDto;
use App\DTOs\TourBookingRequestDto;
use App\DTOs\UserDto;
use App\Exceptions\Booking\InsufficientSeatsException;
use App\Exceptions\Booking\TourNotActiveException;
use App\Models\Booking;
use App\Models\TourAvailability;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Database\DatabaseManager;

class BookingService
{
   public function __construct(protected DatabaseManager $databaseManager)
   {
   }

public function createBooking(TourAvailabilityDto $tourAvailabilityDto ,UserDto $userDto)
    {
    
            # $status = ($data['payment_gateway'] === 'cash') ? 'confirmed' : 'pending';

            # $booking = Booking::create([
                #    'user_id' => $user->id,
                #   'tour_id' => $availability->tour_id,
                #    'tour_availability_id' => $availability->id,
                #    'travel_date' => $availability->date,
                #    'adults_count' => $data['adults_count'],
                #    'children_count' => $data['children_count'],
                #    'adult_price' => $availability->adult_price,
                #    'child_price' => $childPrice,
                #    'total_price' => $grandTotal,
                #    'status' => $status,
                #    'payment_method' => $data['payment_gateway'], // تأكد من وجود العمود ده
                # ]);

                // إنشاء سجل دفع فقط إذا كان أونلاين
                #if ($data['payment_gateway'] !== 'cash') {
                #    $booking->payments()->create([
                #        'amount' => $grandTotal,
                #        'gateway' => $data['payment_gateway'],
                #        'status' => 'pending',
                #    ]);
                # }

                # $availability->increment('booked', $totalGuests);
                # Cache::forget("tour_slots_{$availability->tour_id}");

                # return $booking;
            
    }

public function validateAvailability(TourBookingRequestDto $tourBookingRequest): void
{
    if (!$tourBookingRequest->tourAvailability->isActive) {
        throw new TourNotActiveException();
    }

    if ($tourBookingRequest->tourAvailability->availableSeats < $tourBookingRequest->adultsCount) {
        throw new InsufficientSeatsException();
    }
    
}
}
