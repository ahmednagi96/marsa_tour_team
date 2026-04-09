<?php

namespace App\Http\Requests\API\Travel;

use App\Http\Requests\API\BaseRequest;

class TripRequest extends BaseRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // منع البحث بكلمات تافهة أو طويلة جداً ترهق السيرفر
            'search'   => 'nullable|string|min:2|max:50',
            
            // التأكد من القيمة المنطقية
            'trending' => 'nullable|boolean',
            
            // حماية السيرفر من طلب كميات ضخمة من الداتا في الصفحة الواحدة
            'per_page' => 'nullable|integer|min:1|max:100',
            
            // اختياري: لو عاوز تضمن إن رقم الصفحة دايمًا موجب
            'page'     => 'nullable|integer|min:1',
        ];
    }
}
