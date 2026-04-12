<?php

namespace App\Models;

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
        "travel_date"=>"date",
        "adult_price"=>"decimal:2",
        "child_price"=>"decimal:2",
        "total_price"=>"decimal:2",
        "status"=>BookingStatus::class,



    ];

    /** @return BelongsTo */
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

        /** @return BelongsTo */
    public function tour():BelongsTo{
        return $this->belongsTo(Tour::class);
    }

    public  function tourAvailability(): BelongsTo{
        return $this->belongsTo(TourAvailability::class);
    }




}
