<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $privacy_id
 * @property string $locale
 * @property string|null $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation wherePrivacyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivacyTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PrivacyTranslation extends Model
{
    use HasFactory;
    protected $table="privacys_translations";
    protected $guarded=[];
}

