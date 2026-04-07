<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TourTranslation>
 */
class TourTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           "name"=>fake()->title(),
           "country"=>fake()->country(),
           "city"=>fake()->city(),
           "street"=>fake()->streetAddress(),
           "description"=>fake()->paragraph(2),
           "services"=>fake()->words(5),
           "additional_data"=>fake()->words(5),
        ];
    }
}
