<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Question;
use App\Http\Requests\CreateQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Config;
use Response;
use Hash;
use File;
use Crypt;
use Validator;

class QuestionsController extends Controller
{
	/*
	* Questions listing
	*/
    public function index(Request $request)
    {
		$user_id = user_id();
		$number_of_records = Config::get('constant.per_page');
		$questions = Question::where('created_by',$user_id)->orderBy('created_at', 'desc')->paginate($number_of_records);
        if ($request->ajax()) {
            return view('users.questions.questionPagination', compact('user_id','questions'));
        }

        return view('users.questions.index',compact('user_id','questions'));
    }
    // CREATE QUESTION FORM 
    public function create($user_id)
    {
        $user_id = user_id();
        return view('users.questions.create',compact('user_id'));
    }

    public function question_save(createQuestionRequest $request, $user_id){
        if($request->ajax()){
            $data = array();
            foreach($request->all() as $key=>$value){
                
                if($key == 'question'){
                    foreach($value as $k=>$v){
                        $data[$k]['question'] = $v;
                    }
                }
                if($key == 'answer'){
                    foreach($value as $k=>$v){
                        $data[$k]['answer'] = $v;
                    }
                }
            }
        
            $error = false;
            
            foreach($data as $key=>$value){
                $value['created_by'] = $user_id;
                $slug = Str::slug($value['question'], '-');
                $value['slug'] = $slug;
               // dd($value);
                $dataa = Question::create($value);
                if(!$dataa){
                    $error = true;
                } 
            }
            
            if(!$error){
                return Response::json(array(
                      'success'=>true,
                     ), 200);
                 
            }
        }

    }

    public function question_edit($ques_id)
    {
        
        $ques = Question::where('id',$ques_id)->get();
        if(count($ques)>0){
            $ques =$ques[0];
            $view = view("modal.questionEdit",compact('ques'))->render();
            $success = true;
        }else{
            $view = '';
            $success = false;
        }
        
        return Response::json(array(
          'success'=>$success,
          'data'=>$view
         ), 200);
    }
	
    public function question_update(UpdateQuestionRequest $request,$ques_id){
        $data=array();
        $result =array();
        $requestData = Question::where('id',$ques_id);
        if($request->ajax()){
            $data =array();
            $data['question']= $request->question;
            $data['answer'] = $request->answer;
            $slug = Str::slug($request->question, '-');
            $data['slug'] = $slug;
            
            $requestData->update($data);
              
            $result['success'] = true;
            $result['question'] = Str::limit($request->question, 50);
            $result['answer'] = Str::limit($request->answer, 50);

           return Response::json($result, 200);
        }
    }
    public function question_delete($ques_id){
        if($ques_id){
            Question::where('id',$ques_id)->delete();
            $result =array('success' => true);  
            return Response::json($result, 200);
        }
    }
}
