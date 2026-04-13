<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tour;
use App\Models\TourAvailability;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Trip::factory()->count(10)->create();
        # Tour::factory()->count(10)->create();
       // $tour=Tour::find(1);
       // $tour->update(['photos'=>null]);

      # foreach(Tour::all() as $tour) {

       # $tour->update([
        #    'default_child_price'=>rand(10,100),
        #   'child_age_limit'=>random_int(10,15),
        #    'allows_children'=>random_int(0,1)
       # ]);

       TourAvailability::create([
        'tour_id'=>1,
        'date'=>Carbon::now()->addDays(2),
        'capacity'=>20,
        'is_active'=>1
       ]);
       


    }
}
