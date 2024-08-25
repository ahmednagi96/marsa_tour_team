<?php

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{

    public function definition(): array
    {
        $offer_percent=$this->faker->numberBetween(0,100);
        $tour_id=$this->faker->unique()->randomElement(Tour::where('is_active',1)->get()->pluck('id')->toArray());
        return [
            'tour_id'=>$tour_id,
            'offer_price_percent'=>$offer_percent,
            'offer_price_value'=>(Tour::where('id',$tour_id)->first()->price * (100 - $offer_percent))/100,
            'special_price_start'=>$this->faker->dateTimeBetween('- 1 week',now()),
            'special_price_end'=>$this->faker->dateTimeBetween(now(),'+ 1 week',),
        ];
    }
}
