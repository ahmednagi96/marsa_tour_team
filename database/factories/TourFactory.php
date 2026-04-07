<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "trip_id"=>fake()->numberBetween(1,10),
            "duration"=>fake()->randomDigitNot(0),
            "price"=>fake()->randomElements([100.00 , 250.00 , 500.00]),
            "is_active"=>fake()->boolean(),
            "is_favourite"=>fake()->boolean(),
            "photos"=>fake()->imageUrl(),
        ];
    }
}
