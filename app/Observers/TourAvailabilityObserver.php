<?php

namespace App\Observers;

use App\Models\TourAvailability;

class TourAvailabilityObserver
{
    /**
     * Handle the Tour "saving" event.
     */
    public function creating(TourAvailability $tour): void
    {
        $tour->adult_price = $tour->tour->getCalculatedSalePrice();
        $tour->child_price = $tour->tour->default_child_price;
    }

}
