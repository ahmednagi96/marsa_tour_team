<?php

namespace App\Http\Requests\API\Travel;

use App\Http\Requests\API\BaseRequest;

class TourRequest extends BaseRequest
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
            'search'   => 'nullable|string|max:100',
            // التأكد من القيمة المنطقية
            'active' => 'nullable|boolean|in:0,1',
            'favourite' => 'nullable|boolean|in:0,1',
            'discounts' => 'nullable|boolean|in:0,1',
                        
            // حماية السيرفر من طلب كميات ضخمة من الداتا في الصفحة الواحدة
            'per_page' => 'nullable|integer|min:1|max:100',
            
            // اختياري: لو عاوز تضمن إن رقم الصفحة دايمًا موجب
            'page'     => 'nullable|integer|min:1',
        ];
    }
}
