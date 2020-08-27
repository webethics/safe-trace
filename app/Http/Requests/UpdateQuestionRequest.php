<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
   

    public function rules()
    {
        return [
            'question'    => [
                'required',
            ],
            'answer'    => [
                'required'
            ],
			
        ];
    }
	
	 // public function messages()
  //   {
  //       return [
  //           'mobile_number.regex' => 'Your Mobile Number should valid.',
  //           'email.email' => 'Email should be valid.',
  //       ];
  //   }
	
	
}
