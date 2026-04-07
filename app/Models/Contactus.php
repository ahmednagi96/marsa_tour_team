<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
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
