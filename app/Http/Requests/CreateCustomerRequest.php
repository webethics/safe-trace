<?php
namespace App\Http\Requests;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;


class createCustomerRequest extends FormRequest
{
   /*  public function authorize()
    {
        return \Gate::allows('user_create');
    }
 */
 

    public function rules()
    {
		$rules = [];
		
		foreach ($this->request->get('customer_name') as $index => $val) {
			$rules['customer_name.' . $index] = 'required';
		}
		foreach ($this->request->get('customer_email') as $index => $val) {
			$rules['customer_email.' . $index] = [
				'required','email'
			];
		} 
		foreach ($this->request->get('customer_text') as $index => $val) {
			$rules['customer_text.' . $index] =  [
				'required','regex:/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i'
			];
		} 


		return $rules;
	
    }
	public function messages()
    {
		
		return [
          'customer_name.*.required' => 'Customer Name field is required.',
          'customer_text.*.required' => 'Customer Phone Number field is required.',
          'customer_email.*.required' => 'Customer Email field is required.',
          'customer_email.*.email' => 'Customer Email field is not valid.',
          'customer_text.*.regex' => 'Customer Phone Number should be valid.',
        ];
			 
    }
	
}
