<?php

namespace App\Models;

use App\Observers\TourObserver;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

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
#[ObservedBy([TourObserver::class])]
class Tour extends Model
{
    use HasFactory, Translatable;

    protected $table = "tours";
    protected $fillable = [
        "trip_id",
        "duration",
        "price",
        "discount_type",
        "discount_value",
        "sale_price",
        "sale_start",
        "sale_end",
        "is_active",
        "is_favourite",
        "photos",
        "video",

    ];

    /** @return BelongsTo */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class, 'trip_id')->withDefault(['name' => "Not Found"]);
    }

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i:s',
        'updated_at' => 'datetime:d-m-Y H:i:s',
        'is_active' => 'boolean',
        'is_favourite' => 'boolean',
        'price' => 'decimal:2',
        'photos' => 'json',
    ];
    public $translatedAttributes = ['name', 'description', 'country', 'city', 'street', 'services', 'additional_data'];

    /**
     * الـ Logic المركزي لحساب السعر النهائي
     * وضعناه في ميثود منفصلة عشان نستخدمها في كذا مكان (DRY)
     */
    /** @return float */
    public function getCalculatedSalePrice(): float
    {
        if ($this->discount_value > 0) {
            if ($this->discount_type === 'percentage') {
                return (float) ($this->price - ($this->price * ($this->discount_value / 100)));
            }
            return (float) max(0, $this->price - $this->discount_value);
        }
        return (float) $this->price;
    }


    /**
     * الـ Accessor السحري
     * ده اللي الـ API هيستخدمه لعرض السعر الحقيقي لليوزر حالاً
     * بيظهر في الـ JSON كـ current_price
     */
    public function getCurrentPriceAttribute(): float
    {
        $now = now();

        // هل الخصم فعال وصالح للاستخدام "دلوقتي"؟
        if (
            $this->discount_value > 0 &&
            $this->sale_start && $this->sale_end &&
            $now->between($this->sale_start, $this->sale_end)
        ) {

            return (float) $this->sale_price;
        }

        // لو التاريخ انتهى أو لسه م بدأش، ارجع للسعر الأصلي
        return (float) $this->price;
    }

    /**
     * ميثود إضافية بتعرفك لو الرحلة عليها عرض "شغال" حالياً
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->current_price < $this->price;
    }


    /**
     * ميثود إضافية لمعرفة "مبلغ الخصم" نفسه (كام جنيه توفير؟)
     */
    public function getSavedAmountAttribute()
    {
        return $this->price - $this->current_price;
    }

   
    protected static function booted()
    {
        // أول ما رحلة تضاف، تتعدل، أو تتحذف
        $clearTripsCache = function () {
            Cache::tags(['tours'])->flush(); 
        };
        static::created($clearTripsCache);
        static::updated($clearTripsCache);
        static::deleted($clearTripsCache);
    }


    //search


    /** @return Builder */
    public function scopeFilter(Builder $query, ?string $searchTerm): Builder
    {
        if (empty($searchTerm)) {
            return $query;
        }

        $words = explode(' ', $searchTerm);

        return $query->where(function ($q) use ($words) {
            foreach ($words as $word) {
                $q->where(function ($sub) use ($word) {
                    $sub->whereTranslationLike('name', "%{$word}%")
                        ->orWhereTranslationLike('description', "%{$word}%")
                        ->orWhereTranslationLike('city', "%{$word}%")
                        ->orWhereTranslationLike('street', "%{$word}%")
                        ->orWhereTranslationLike('country', "%{$word}%");
                });
            }
        });
    }

    public function scopeActive(Builder $query, bool $status = false): Builder
    {
        return $query->when(filter_var($status, FILTER_VALIDATE_BOOLEAN), function ($q) {
            return $q->where("is_active", 1);
        });
    }
    public function scopeFavourite(Builder $query, bool $status = false): Builder
    {
        return $query->when(filter_var($status, FILTER_VALIDATE_BOOLEAN), function ($q) {
            return $q->where("is_favourite", 1);
        });
    }
}
