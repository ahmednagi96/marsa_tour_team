<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourFavourite extends Model
{
    use HasFactory;
    protected $table="tour_favourites";

    protected $guarded=[];
}
