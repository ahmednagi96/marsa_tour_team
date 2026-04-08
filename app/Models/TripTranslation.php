<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $trip_id
 * @property string $locale
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tour> $tours
 * @property-read int|null $tours_count
 * @method static \Database\Factories\TripTranslationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereTripId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TripTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
