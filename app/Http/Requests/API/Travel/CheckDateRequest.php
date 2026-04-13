<?php 

namespace App\Http\Requests\API\Travel;

use App\Http\Requests\API\BaseRequest;

class CheckDateRequest extends BaseRequest
{
    public function rules():array{
        return [
            'date' => 'required|date_format:d-m-Y|after:today',
        ] ;
    }
}