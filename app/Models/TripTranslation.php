<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripTranslation extends Model
{

    use HasFactory;
    protected $table="trip_translations";
    protected $fillable = [
        "name","description"
    ];
    public function tours(){
        return $this->hasMany(Tour::class,'trip_id');
    }
}
