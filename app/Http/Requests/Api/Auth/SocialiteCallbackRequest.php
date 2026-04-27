<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\BaseRequest;

class SocialiteCallbackRequest extends BaseRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   
     public function rules(): array
     {
         return [
             'code' => ['required', 'string'],
         ];
     }
 
     public function messages(): array
     {
         return [
             'code.required' => 'The authorization code from the provider is required.',
         ];
     }
}
