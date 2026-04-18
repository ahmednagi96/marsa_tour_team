<?php 

namespace App\Warehouses\Travel;

use App\Models\TourAvailability;

class TourAvailabiltyManager
{
    public function reserveSeats(int $tourAvailabiltyId,int $countSeats): void 
    {      
       TourAvailability::find($tourAvailabiltyId)?->increment("booked",$countSeats);
    }
}