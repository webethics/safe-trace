<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailTemplateRequest;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Storage;
use Auth;
use Config;
use Response;
use Session;
class EmailController extends Controller
{
	//private $photos_path;
	public function __construct()
    {
		//$this->photos_path = public_path('/uploads/logo/');
    }
   
	/*
	* SETTING LAYOUT 
	*/
    public function index()
    {
		$emailTemplate =  EmailTemplate::all();
        return view('email.index',compact('emailTemplate'));
    }
	
	/*
	* EDIT EMAIL TEMPLATE 
	*/
	public function email_template_edit($id){
		
		//display edit page of email template
			$result = EmailTemplate::where('id', '=' , $id)->get();
			
			return view('email.edit_email' , compact('result'));
	}

	public function email_template_update(EmailTemplateRequest $request){

       
		 $email_id = $request->input('email_id');
		//check field validation
		
	    $title = $request->input('title');
	    $subject = $request->input('subject');
	    $description = $request->input('description');
	    // update email template
	    $data = array('title'=>$title,'subject'=>$subject,'content'=>$description);
	    $email_update  = EmailTemplate::where('id', '=', $email_id);
		
		$email_update->update($data);
		Session::flash('success', 'Email Template has been Updated.');
		return redirect('email/edit/'.$email_id); 

	  
	}
	
}
