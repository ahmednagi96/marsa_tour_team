<?php 

namespace App\DTOs;

use App\Models\TourAvailability;

class TourBookingRequestDto
{
    public function __construct(
        public TourAvailabilityDto $tourAvailability,
        public int $adultsCount,
        public int $childrenCount,
    )
    {
    }

    public static function fromEloquentModel(int $tourAvailabilityId,int $adultsCount,int $childrenCount):TourBookingRequestDto{
        return new self(TourAvailabilityDto::fromEloquentModel(TourAvailability::find($tourAvailabilityId)), $adultsCount, $childrenCount);
    }

}