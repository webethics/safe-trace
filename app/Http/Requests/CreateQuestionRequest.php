<?php
namespace App\Http\Requests;

use App\Models\User;
use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;


class createQuestionRequest extends FormRequest
{
   /*  public function authorize()
    {
        return \Gate::allows('user_create');
    }
 */
 

    public function rules()
    {
		$rules = [];
		
		foreach ($this->request->get('question') as $index => $val) {
			$rules['question.' . $index] = 'required';
		}
		foreach ($this->request->get('answer') as $index => $val) {
			$rules['answer.' . $index] = 'required';
		}

		return $rules;
	
    }
	public function messages()
    {
		
		return [
          'question.*.required' => 'Question field is required.',
          'answer.*.required' => 'Answer field is required.',
        ];
			 
    }
	
}
