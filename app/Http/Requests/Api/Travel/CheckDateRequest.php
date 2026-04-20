<?php 

namespace App\Http\Requests\Api\Travel;

use App\Http\Requests\Api\BaseRequest;

class CheckDateRequest extends BaseRequest
{
    public function rules():array{
        return [
            'date' => 'required|date_format:d-m-Y|after:today',
        ] ;
    }
}