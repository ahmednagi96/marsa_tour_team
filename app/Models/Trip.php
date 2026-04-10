<?php

namespace App\Models;

use App\Enums\TripTrending;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string|null $photo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tour> $tours
 * @property-read int|null $tours_count
 * @property-read \App\Models\TripTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TripTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Database\Factories\TripFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Trip listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Trip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
class Trip extends BaseModel
{

    use HasFactory, Translatable;
    protected $table = "trips";
    protected $fillable = [
        'photo',
        'created_at',
        'trending'
    ];
    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
        'trending' => TripTrending::class,
    ];
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'trip_id');
    }
    /** 
     * جلب الرحلات التريند
     * استخدام: Trip::trend()->get()
     * @return Builder 
     */
    public function scopeTrend(Builder $query, ?bool $status = null): Builder
    {
        return $query->when(!is_null($status),function ($q) use ($status) {
             return $q->where('trending', $status);
        });
    }
    protected static function booted()
    {
        $clearTripsCache = function () {
            Cache::tags(['trips'])->flush();
        };
        static::saved($clearTripsCache);
        static::deleted($clearTripsCache);
    }

    /** @return Builder */
    public function scopeFilter(Builder $query, ?string $searchTerm): Builder
    {
        if (empty($searchTerm)) {
            return $query;
        }

        $words = explode(' ', $searchTerm);

        return $query->where(function ($q) use ($words) {
            foreach ($words as $word) {
                // هنا التغيير: نستخدم where (التي تعمل كـ AND) 
                // عشان نضمن إن كل كلمة في جملة البحث موجودة فعلياً
                $q->where(function ($sub) use ($word) {
                    $sub->whereTranslationLike('name', "%{$word}%")
                        ->orWhereTranslationLike('description', "%{$word}%");
                });
            }
        });
    }

    public $translatedAttributes = ['name', 'description'];

    /**
     * Get the trip's photo full URL.
     * @return Attribute
     */
    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->photo
                ? asset('images/trips/' . $this->photo)
                : asset('images/default.jpeg'),
        );
    }
}
