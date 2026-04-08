<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Astrotomic\Translatable\Translatable;
/**
 * @property int $id
 * @property int $tour_id
 * @property string|null $offer_price_value
 * @property int|null $offer_price_percent
 * @property string|null $special_price_start
 * @property string|null $special_price_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tour $tour
 * @property-read \App\Models\OfferTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OfferTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\OfferFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Offer listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Offer translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOfferPricePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereOfferPriceValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereSpecialPriceEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereSpecialPriceStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereTourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Offer withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
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
