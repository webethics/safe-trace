<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
class sendEmailNotification extends FormRequest
{
   

    public function rules()
    {
        return [
            'email'    => [
                'required','email'
            ],
           
        ];
    }
	
}
