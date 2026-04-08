<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $offer_id
 * @property string $locale
 * @property string|null $offer_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\OfferTranslationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereOfferName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfferTranslation extends Model
{
    use HasFactory;
    protected $table="offer_translations";
    protected $guarded=[];
}
