<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $contactus_id
 * @property string $locale
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereContactusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactusTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ContactusTranslation extends Model
{
    use HasFactory;
    protected $table="contactus_translations";
    protected $fillable = [
        "name"
    ];
}
