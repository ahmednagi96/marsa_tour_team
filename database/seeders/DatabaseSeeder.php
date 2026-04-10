<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tour;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Trip::factory()->count(10)->create();
        //Tour::factory()->create();
        $tour=Tour::find(1);
        $tour->update(['photos'=>null]);

    }
}
