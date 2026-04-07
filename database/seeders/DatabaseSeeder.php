<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\Admin::factory(10)->create();
        \App\Models\Admin::factory()->create([
            'name' => 'Admin',
            'email' => 'SuperAdmin@email.com',
            'password'=>Hash::make('password'),
        ]);
        // \App\Models\Trip::factory(10)->create();
        // \App\Models\TripTranslation::factory(10)->create();

        // \App\Models\Tour::factory(30)->create();
        // \App\Models\TourTranslation::factory(30)->create();

        // \App\Models\Offer::factory(15)->create();
        // \App\Models\OfferTranslation::factory(15)->create();

        // \App\Models\Offer::factory(4)->create();
    }
}
