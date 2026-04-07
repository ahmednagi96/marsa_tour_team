<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //name ,email ,password ,role ,profile_photo_path
        return [
            "name" =>fake()->name(),
            "email"=>fake()->unique()->safeEmail(),
            "password"=> Hash::make('password'),
            "profile_photo_path"=>fake()->imageUrl(),
        ];
    }
}
