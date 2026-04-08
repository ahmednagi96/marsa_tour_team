<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $tour_id
 * @property string $locale
 * @property string $name
 * @property string|null $country
 * @property string|null $city
 * @property string|null $street
 * @property string|null $description
 * @property string|null $services
 * @property string|null $additional_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\TourTranslationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereAdditionalData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereTourId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TourTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TourTranslation extends Model
{
    use HasFactory;
    protected $table="tour_translations";
    protected $guarded = [];
}
