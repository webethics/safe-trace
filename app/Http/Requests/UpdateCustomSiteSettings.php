<?php
namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UpdateCustomSiteSettings extends FormRequest
{
   /*  public function authorize()
    {
        return \Gate::allows('user_create');
    }
 */
    public function rules()
    {
        return [
			'background_color'     => [
                'required',
            ],
			 'font_color'     => [
                'required',
            ],
			 'welcome_text'     => [
                'required',
            ],
			/* ,
			'api_name'     => [
                'required',
            ],
            'api_key'  => [
				'required',
            ], */
        ];
    }
	
}
