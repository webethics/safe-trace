<?php

namespace App\Http\Requests;

use App\Models\Request;
use Illuminate\Foundation\Http\FormRequest;

class StoreCaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
   
    }

    public function rules()
    {
		
		$rules = [	
			'priority' => [
                'required',
            ],
			'data_archive' => [
                'required',
            ],
        ];
		//pr($this->request->get('name')[0]);
		if(empty($this->request->get('name')[0]) && empty($this->request->get('company')[0]) && empty($this->request->get('url')[0]) && empty($this->request->get('social_type')[0]) && empty($this->request->get('social_name')[0])){
		 foreach($this->request->get('name') as $key => $val)
		 {
			$rules['name.'.$key] = 'required';
		 } 
		 foreach($this->request->get('company') as $key => $val)
		 {
			$rules['company.'.$key] = 'required';
		 }  
		 foreach($this->request->get('url') as $key => $val)
		 {
			$rules['url.'.$key] = 'required|url';
		 }  
		 foreach($this->request->get('social_type') as $key => $val)
		 {
			$rules['social_type.'.$key] = 'required';
		 } 
		 foreach($this->request->get('social_name') as $key => $val)
		 {
			$rules['social_name.'.$key] = 'required';
		 }  
		}else{
			
		foreach($this->request->get('name') as $key => $val)
		 {
			//$rules['name.'.$key] = '';
			$this->request->remove('name.'.$key);
		 } 
		 foreach($this->request->get('company') as $key => $val)
		 {
			//$rules['company.'.$key] = '';
			$this->request->remove('company.'.$key);
		 }
		 foreach($this->request->get('url') as $key => $val)
		 {
			$this->request->remove('url.'.$key);
		 }  
		 foreach($this->request->get('social_type') as $key => $val)
		 {
			$this->request->remove('social_type.'.$key);
		 } 
		 foreach($this->request->get('social_name') as $key => $val)
		 {
			$this->request->remove('social_name.'.$key);
		 }  		 
		}
		
        
		return $rules;
    } 
	
	public function messages()
    {
		//$messages = ['priority.name.*' => 'Test go'];
		
		 foreach($this->request->get('name') as $key => $val)
		 {
			$messages['name.'.$key.'.required'] = 'You need to enter one of these fields name,company,url or social Media.';
		 }
		 
		/*  foreach($this->request->get('name') as $key => $val)
		 {
			$messages['name.'.$key.'.required'] = 'The name field is required.';
		 } 
		 
		 foreach($this->request->get('company') as $key => $val)
		 {
			$messages['company.'.$key.'.required'] = 'The company field is required.';
		 } 
		  foreach($this->request->get('url') as $key => $val)
		 {
			$messages['url.'.$key.'.required'] = 'The url field is required.';
			$messages['url.'.$key.'.url'] = 'The url format is invalid.';
		 } 
		 foreach($this->request->get('social_type') as $key => $val)
		 {
			$messages['social_type.'.$key.'.required'] = 'Please select social type.';
		 } 
		 foreach($this->request->get('social_name') as $key => $val)
		 {
			$messages['social_name.'.$key.'.required'] = 'Please enter social name.';
		 }   */
		return $messages;
    }
}
