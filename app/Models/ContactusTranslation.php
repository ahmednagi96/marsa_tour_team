<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactusTranslation extends Model
{
    use HasFactory;
    protected $table="contactus_translations";
    protected $fillable = [
        "name"
    ];
}
