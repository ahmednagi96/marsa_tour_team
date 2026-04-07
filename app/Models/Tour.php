<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tour extends Model
{
    use HasFactory,Translatable;

    protected $table="tours";
    protected $fillable=[
        'trip_id',
        'duration',
        'price',
        'is_active',
        'is_favourite',
        'photos',
        'video'
    ];

    public function trip():BelongsTo
    {
        return $this->belongsTo(Trip::class,'trip_id')->withDefault(['name'=>"Not Found"]);

    }
    public function offer():HasOne
    {
        return $this->hasOne(Offer::class,'tour_id');
    }
    protected $casts = [
        'created_at'=>'datetime:d-m-Y H:i:s',
        'updated_at'=>'datetime:d-m-Y H:i:s',
        'is_active'=>'boolean',
        'is_favourite'=>'boolean',
        'price'=>'decimal:2'
    ];
    protected $with=['translations'];
    protected $hidden=['translations'];
    public $translatedAttributes=['name','description','country','city','street','services','additional_data'];

    public function getHomeData(){
        return [
            'id' => $this->id,
            'trip_id' => $this->trip_id,
            'trip_name' => $this->trip->name,
            'duration'=>$this->duration,
            'price'=>$this->price,
            'offer_price_percent'=>isset($this->offer)?$this->offer->offer_price_percent:null,
            'offer_price_value'=>isset($this->offer)?$this->offer->offer_price_value:null,
            'photos' =>is_null($this->photos)?null:BASEURLPHOTO.json_decode($this->photos,true)[0],
            'name' => $this->name,
            'country'=>$this->country,
            'city'=>$this->city,
            'street'=>$this->street
        ];
    }
    public function formatPhotos()
    {
        if (is_null($this->photos)) {
            return null;
        }
        $photosArray = json_decode($this->photos, true);
        if (is_array($photosArray)) {
            return array_map(function ($photo) {
                return BASEURLPHOTO . $photo;
            }, $photosArray);
        }
        return null;
    }
}
