<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRequestRequest;
use App\Http\Requests\StoreRequestRequest;
use App\Http\Requests\UpdateRequestRequest;
use App\Models\RequestCase;

class RequestsReplyController extends Controller
{
    public function index()
    {
        //abort_unless(\Gate::allows('product_access'), 403);
		
		$requests = RequestCase::all();
		//pr($requests);
       return view('users.analyst.requests.index', compact('requests'));
      
    }
	
	public function requestreply(RequestCase $post, $request_id)
    {
        //abort_unless(\Gate::allows('product_access'), 403);
		
		//$requests = Request::all();
		//pr($requests);
       return view('users.analyst.requests.show');
      
    }

    public function create()
    {
        //abort_unless(\Gate::allows('request_create'), 403);

        return view('users.requests.create');
    }

    public function store(StoreCaseRequest $post)
    {
		

		
		
		   if($post->ajax()){
			 
            $name = $post->name;
			echo $name[0]; 
           // $content = Input::get( 'message' );
			
		  } 
        //abort_unless(\Gate::allows('user'), 403);
        //$data =array();
		/* $data['name']= $request->name;
		$data['case_number']= 'C-234556-23456';
		$data['requested_user_id']= '4';
		$data['social_media']= 'fb';
		$data['company']= $request->company;
		$data['url']= $request->url;
		$data['other_info']= $request->other_info;
		$data['priority']= $request->priority;
		$data['data_archive']= implode('|',$request->data_archive);
     // print_r($request->all()); die;
        $request = Request::create($data);

        return redirect()->route('admin.requests.index'); */
    }

    public function edit(RequestCase $request)
    {
        abort_unless(\Gate::allows('request_edit'), 403);

        return view('users.requests.edit', compact('request'));
    }

    public function update(UpdateRequestRequest $post, Request $request)
    {
        abort_unless(\Gate::allows('request_edit'), 403);
		
		$data =array();
		$data['name']= $post->name;
		$data['case_number']= 'C-234556-23456';
		$data['requested_user_id']= '4';
		$data['social_media']= 'fb';
		$data['company']= $post->company;
		$data['url']= $post->url;
		$data['other_info']= $post->other_info;
		$data['priority']= $post->priority;
		$data['data_archive']= implode('|',$post->data_archive);

		//$request->update($post->all());
        $request->update($data);

        return redirect()->route('users.requests.index');
    }
  
}
