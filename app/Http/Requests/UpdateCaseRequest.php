<?php

namespace App\Http\Requests;

use App\Modals\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequestRequest extends FormRequest
{
    public function authorize()
    {
        return \Gate::allows('request_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
        ];
    }
}
