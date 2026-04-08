<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
/**
 * @property int $id
 * @property string|null $photo
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ContactusTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ContactusTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus translated()
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contactus withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
class Contactus extends Model
{
    use HasFactory,Translatable;
    protected $fillable=[
        'photo','email'
    ];
    protected $casts = [
        'created_at'=>'datetime:d-m-Y H:i:s',
        'updated_at'=>'datetime:d-m-Y H:i:s',
    ];
    protected $table="contactus";
    protected $with=['translations'];
    protected $hidden=['translations'];
    public $translatedAttributes=['name'];
}
