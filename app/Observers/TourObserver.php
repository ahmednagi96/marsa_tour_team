<?php

namespace App\Observers;

use App\Models\Tour;

class TourObserver
{
    /**
     * Handle the Tour "saving" event.
     */
    public function saving(Tour $tour): void
    {
        $tour->sale_price = $tour->getCalculatedSalePrice();
        

    }

   
}
