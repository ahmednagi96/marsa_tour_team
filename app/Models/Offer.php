<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Astrotomic\Translatable\Translatable;
class Offer extends Model
{
    use HasFactory,Translatable;
    protected $table="offers";
    protected $fillable=[
        'tour_id',
        'offer_price_percent',
        'offer_price_value',
        'special_price_start',
        'special_price_end',
    ];
    public function tour():BelongsTo{
        return $this->belongsTo(Tour::class,'tour_id');
    }
    protected $casts = [
        'created_at'=>'datetime:d-m-Y H:i:s',
        'updated_at'=>'datetime:d-m-Y H:i:s',
        'offer_price_value'=>'decimal:2'
    ];
    protected $with=['translations'];
    protected $hidden=['translations'];
    public $translatedAttributes=['offer_name'];

    public function getSinglePhoto(){
        if(isset($this->tour) && !is_null($this->tour->photos)){
        return    $photo=BASEURLPHOTO.json_decode($this->tour->photos,true)[0];
        }
        return null;

    }
    public function getSpecialPriceStartAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getSpecialPriceEndAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
