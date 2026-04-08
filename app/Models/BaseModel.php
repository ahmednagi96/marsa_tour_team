<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
    protected $casts = [
        'created_at'=>'datetime:d-m-Y H:i:s',
        'updated_at'=>'datetime:d-m-Y H:i:s',
    ];
    protected $with = ['translations'];
    protected $hidden = ['translations'];
}

