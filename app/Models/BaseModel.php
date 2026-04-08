<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
   
    protected $with = ['translations'];
    protected $hidden = ['translations'];
}

