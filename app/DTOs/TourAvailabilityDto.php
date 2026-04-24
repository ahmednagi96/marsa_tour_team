<?php 

namespace App\DTOs;

use App\Models\TourAvailability;

readonly class TourAvailabilityDto{
    public function __construct(
      public int $id,
      public int $tourId,
      public string $date,
      public int $capacity,
      public int $booked,
      public float $adultPrice,
      public float $childPrice,
      public int $isActive,
      public int $availableSeats,
    )
    {
        
    }

    /** @param TourAvailability $model
     *  @return TourAvailabilityDto
     */
    public static function fromEloquentModel(TourAvailability $model): TourAvailabilityDto{
        return new self($model->id,$model->tour_id,$model->date,$model->capacity,$model->booked, $model->adult_price,$model->child_price,$model->is_active,$model->available_seats);
    }
}