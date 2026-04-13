<?php

namespace App\Models;

use App\Observers\TourAvailabilityObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

#[ObservedBy([TourAvailabilityObserver::class])]

class TourAvailability extends Model
{
    use HasFactory;
    protected $fillable = [
        "tour_id",
        "date",
        "capacity",
        "booked",
        "adult_price",
        "child_price",
        "is_active",
    ];
   # protected $touches = ['tour']; // اسم العلاقة اللي في الموديل

    protected static function booted()
    {
        $clearTripsCache = function () {
            Cache::tags(['tour_availabilties'])->flush(); 
        };
        static::created($clearTripsCache);
        static::updated($clearTripsCache);
        static::deleted($clearTripsCache);
    }


    /** @return  BelongsTo */
    public function tour():BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    /** @return int */
    public function getAvailableSeatsAttribute():int
    {
        $seats = $this->capacity - $this->booked;
        return $seats > 0 ? $seats : 0;
    }


    public function getFinalChildPriceAttribute()
    {
        // لو مفيش سعر طفل خاص باليوم ده، هات السعر الافتراضي من موديل الجولة
        return $this->child_price ?? $this->tour->default_child_price;
    }


}
