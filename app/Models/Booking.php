<?php

namespace App\Models;

use App\DTOs\TourAvailabilityDto;
use App\DTOs\TourBookingRequestDto;
use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;



    protected $fillable = [
        "user_id",
        "tour_id",
        "tour_availability_id",
        "travel_date",
        "adults_count",
        "children_count",
        "adult_price",
        "child_price",
        "total_price",
        "status",
    ];


    protected $casts = [
        "travel_date" => "date",
        "adult_price" => "decimal:2",
        "child_price" => "decimal:2",
        "total_price" => "decimal:2",
        "status" => BookingStatus::class,



    ];

    /** @return BelongsTo */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo */
    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public  function tourAvailability(): BelongsTo
    {
        return $this->belongsTo(TourAvailability::class);
    }

    // داخل موديل Booking
    public static function createFromDto(TourBookingRequestDto $dto, int $userId): self
    {
        $booking = self::make([
            "user_id"              => $userId, // تصحيح: كانت user_if
            "tour_id"              => $dto->tourAvailability->tourId,
            "tour_availability_id" => $dto->tourAvailability->id,
            "travel_date"          => $dto->tourAvailability->date,
            "adults_count"         => $dto->adultsCount,
            "children_count"       => $dto->childrenCount,
            "status"               => BookingStatus::PENDING
        ]);

        $booking->calculatePrices($dto);

        $booking->save();

        return $booking;
    }

    protected function calculatePrices(TourBookingRequestDto $dto): void
    {
        $this->child_price = $dto->childrenCount * $dto->tourAvailability->childPrice;
        $this->adult_price = $dto->adultsCount * $dto->tourAvailability->adultPrice;
        $this->total_price = $this->child_price + $this->adult_price;
    }
}
