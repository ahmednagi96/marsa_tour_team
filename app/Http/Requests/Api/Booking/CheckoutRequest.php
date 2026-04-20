<?php

namespace App\Http\Requests\Api\Booking;

use App\Http\Requests\Api\BaseRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends BaseRequest
{
    public function rules()
    {
        return [
            // بنطلب الـ ID بتاع اليوم المحدد
            'availability_id' => 'required|exists:tour_availabilities,id',
            'adults_count'    => 'required|integer|min:1|max:50', // حطينا max للأمان
            'children_count'  => 'required|integer|min:0|max:50',
            
            // اليوزر بيختار من اللي إنت متاح عندك
            'payment_gateway' => [
                'nullable',
                Rule::in(['stripe', 'paymob', 'cash', 'paypal']),
            ],            
            'notes'           => 'nullable|string|max:500',
        ];
    }
}
