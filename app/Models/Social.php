<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Social extends Model
{
    use HasFactory,Translatable;

    protected $table="socials";
    protected $fillable=['link'];
    protected $casts = [
        'created_at'=>'datetime:d-m-Y H:i:s',
        'updated_at'=>'datetime:d-m-Y H:i:s',
    ];
    protected $with=['translations'];
    protected $hidden=['translations'];
    public $translatedAttributes=["title"];
}
