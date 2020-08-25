<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
   

    public function rules()
    {
        return [
            'owner_name'    => [
                'required',
            ],
            'email'    => [
                'required','email'
            ],
			'mobile_number'    => [
               'required','regex:/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i',
            ]
         
        ];
    }
	
	 public function messages()
    {
        return [
            'mobile_number.regex' => 'Your Mobile Number should valid.',
            'email.email' => 'Email should be valid.',
        ];
    }
	
	
}
