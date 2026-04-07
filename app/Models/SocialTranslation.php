<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialTranslation extends Model
{
    use HasFactory;
    protected $table="social_translations";
    protected $fillable = [
    "title"
    ];
}

