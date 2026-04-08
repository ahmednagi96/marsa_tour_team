<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $social_id
 * @property string $locale
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SocialTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SocialTranslation extends Model
{
    use HasFactory;
    protected $table="social_translations";
    protected $fillable = [
    "title"
    ];
}

