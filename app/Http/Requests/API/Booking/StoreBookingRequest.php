<?php 

namespace App\Http\Requests\API\Booking;

use App\Http\Requests\API\BaseRequest;

class StoreBookingRequest extends BaseRequest
{
    public function rules()
{
    return [
        'availability_id' => 'required|exists:tour_availabilities,id',
        'adults_count'    => 'required|integer|min:1',
        'children_count'  => 'required|integer|min:0',
        'payment_gateway' => 'required|in:stripe,paymob,paypal',
    ];
}
}