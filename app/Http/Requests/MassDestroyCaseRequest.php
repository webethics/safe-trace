<?php

namespace App\Http\Requests;

use App\Modals\Request;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class MassDestroyCasetRequest extends FormRequest
{
    public function authorize()
    {
        return abort_if(Gate::denies('request_delete'), 403, '403 Forbidden') ?? true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:requests,id',
        ];
    }
}
