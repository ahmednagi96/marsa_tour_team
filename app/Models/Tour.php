<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $trip_id
 * @property int $duration
 * @property string $price
 * @property bool $is_active
 * @property bool $is_favourite
 * @property string|null $photos
 * @property string|null $video
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Offer|null $offer
 * @property-read \App\Models\TourTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TourTranslation> $translations
 * @property-read int|null $translations_count
 * @property-read \App\Models\Trip $trip
 * @method static \Database\Factories\TourFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tour listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Tour query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereIsFavourite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTripId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
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
