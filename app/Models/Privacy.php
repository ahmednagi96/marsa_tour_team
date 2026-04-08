<?php

namespace App\Models;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PrivacyTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PrivacyTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy query()
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Privacy withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
class Privacy extends Model
{
    use HasFactory,Translatable;

    protected $fillable=["file"];
    protected $table="privacys";

    protected $casts = [
        'created_at'=>'datetime:d-m-Y H:i:s',
        'updated_at'=>'datetime:d-m-Y H:i:s',
    ];
    protected $with=['translations'];
    protected $hidden=['translations'];
    public $translatedAttributes=['text'];
}
