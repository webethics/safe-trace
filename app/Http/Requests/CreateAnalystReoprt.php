<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAnalystReoprt extends FormRequest
{
   
    public function rules()
    {
        return [
            'comment'    => [
                'required',
            ]
        ];
    }
	
	/* public function messages()
    {
         return [
            'mobile_number.regex' => 'Your Mobile Number should be 10 digits.',
            'mobile_number.min' => 'fhfgs.',
        ]; 
    } */
	
	
}
