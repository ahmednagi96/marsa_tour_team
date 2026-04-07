<?php

namespace App\Models;
use Astrotomic\Translatable\Translatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory,Translatable;
    protected $table="trips";
    protected $fillable=[
        'photo','created_at'
    ];
    public function tours():HasMany
    {
        return $this->hasMany(Tour::class,'trip_id');
    }
    protected $casts = [
        'created_at'=>'datetime:d-m-Y H:i:s',
        'updated_at'=>'datetime:d-m-Y H:i:s',
    ];
    protected $with=['translations'];
    protected $hidden=['translations'];
    public $translatedAttributes=['name','description'];

}
