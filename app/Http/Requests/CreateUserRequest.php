<?php
namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class createUserRequest extends FormRequest
{
   /*  public function authorize()
    {
        return \Gate::allows('user_create');
    }
 */
    public function rules()
    {
        return [
            'owner_name'     => [
                'required',
            ],
			'business_name'    => [
                'required',
            ],
			'email*' => [
				'required','email','unique:users'
			],
            'mobile_number'   => [
               'required','numeric','regex:/[0-9]{9}/',
            ], 
			'address'   => [
               'required',
            ], 
			'business_url'   => [
               'required',
            ], 
			
        ];
    }
	public function messages()
    {
		return [
          /* 'password.regex' => 'Your password must contain 1 lower case character 1 upper case character one number and One special character.', */
		  'mobile_number.regex' => 'Your Mobile Number should be minimum 9 digits.',
          'mobile_number.min' => 'fhfgs.',
        ];
			 
    }
	
}
