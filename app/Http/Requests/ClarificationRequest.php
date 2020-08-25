<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ClarificationRequest extends FormRequest
{
   

    public function rules()
    {
        return [
            'clarification'    => [
                'required',
            ]
           
        ];
    }

}
