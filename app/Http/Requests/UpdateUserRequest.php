<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
   

    public function rules()
    {
		if(current_user_role_id()==2){
			 return [
				'owner_name'     => [
					'required',
				],
				'business_name'    => [
					'required',
				],
				'mobile_number'   => [
				   'required','numeric','regex:/[0-9]{9}/',
				], 
				'address'   => [
				   'required',
				], 
				
			   
			];
		}else{
			 return [
				'owner_name'     => [
					'required',
				],
			];
		}
       
    }
	
	 public function messages()
    {
        return [
            'mobile_number.regex' => 'Your Mobile Number should be minimum 9 digits.',
            'mobile_number.min' => 'fhfgs.',
        ];
    }
	
	
}
