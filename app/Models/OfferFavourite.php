<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferFavourite extends Model
{
    use HasFactory;
    protected $table="offer_favourites";

    protected $guarded=[];
}
