<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'code',
        'expired_at',
        'country',
        'country_code',
        'photo',
        'fcm_token'
    ];
    public function getRouteKeyName(): string
    {
        return 'phone';
    }
       protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'=>'datetime:d-m-Y H:i:s',
        'updated_at'=>'datetime:d-m-Y H:i:s',
    ];

    public function tourFavourite():BelongsToMany{
        return $this->belongsToMany(Tour::class,'tour_favourites');
    }
    public function offerFavourite():BelongsToMany{
        return $this->belongsToMany(Offer::class,'offer_favourites');
    }

    public function is_favourite($model,$id){
        return $model::where([['user_id',auth('sanctum')->id()],['tour_id',$id]])->exists()?true:false;
    }

}
