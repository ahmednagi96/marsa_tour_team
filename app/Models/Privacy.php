<?php

namespace App\Models;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
