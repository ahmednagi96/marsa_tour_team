<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Panel;


class Admin extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo_path'
    ];

    const ROLE_ADMIN = "ADMIN";
    const ROLE_SUPER_ADMIN = "SUPER_ADMIN";
    const ROLE_DEFAULT = "ADMIN";

    const ROLES=[
       self::ROLE_ADMIN =>'Admin',
       self::ROLE_SUPER_ADMIN =>'SUPER_ADMIN'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'created_at'=>'datetime',
            'updated_at'=>'datetime'
        ];
    }
    public function isAdmin(){
        return $this->role===self::ROLE_ADMIN;
    }

}
