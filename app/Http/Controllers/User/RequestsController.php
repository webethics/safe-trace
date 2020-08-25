<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCaseRequest;
use App\Http\Requests\StoreCaseRequest;
use App\Http\Requests\UpdateCaseRequest;
use App\Http\Requests\ClarificationRequest;
use App\Models\RequestCase;
use App\Models\User;
use App\Models\Notification;
use App\Models\EmailTemplate;
use Config;
use Response;
use Illuminate\Http\Request;
use DB;
use App\Models\Report;
use App\Models\RequestAttachment;
use App\Models\Comment;
use App\Models\AuditLog;
class RequestsController extends Controller
{
     protected $per_page;
	 
	 public function __construct()
    {
	    
        $this->per_page = Config::get('constant.per_page');
    }
/*===========================================================
	 Listing Request with pagination 
==============================================================*/	
	public function ajaxPagination(Request $request)
    {
		$requests= $this->advance_search($request);
        if(!is_object($requests)) return $requests;
        if ($request->ajax()) {
            return view('users.requests.requestPagination', compact('requests'));
        }
        return view('users.requests.index',compact('requests'));
    }
/*===========================================================
        // SHOW CREATE REQUEST FORM 
==============================================================*/

    public function create()
    {
        //abort_unless(\Gate::allows('request_create'), 403);

        return view('users.requests.create');
    }
/*===========================================================
     // REQUEST STORE INTO DB BY AJAX  
==============================================================*/
    
    public function store(StoreCaseRequest $request)
    {
		if($request->ajax()){

			$data =array();
			$data['name']= rtrim(implode('|',$request->name),'|');
			$rand = rand ( 10000 , 99999 );
		    $date = date('dmy');
		
			$data['case_number']= 'C-'.$date.'-'.$rand;
			$data['requested_user_id']= user_id();
			$social_media = array();
			if(!empty($request->social_type[0])){
			foreach($request->social_type as $key=>$value){
				$social_media[$value] = $request->social_name[$key];
			}
			$data['social_media'] = json_encode($social_media);
			}else{
			$data['social_media'] = '';
			}
			$data['company'] = rtrim(implode('|',$request->company),'|'); //implode('|',$request->company);
			$data['url'] = rtrim(implode('|',$request->url),'|'); //implode('|',$request->url);
			$data['other_info'] = $request->other_info;
			$data['priority'] = $request->priority;
			$data['data_archive'] = implode('|',$request->data_archive);
			
			$requestData = RequestCase::create($data);
			
			$result =array('success' => true);	
			
			
			//sending notification to the super admin about the new request.
			$sender_id = $data['requested_user_id'];
			$reciever_id = 1;
			$requested_id = $requestData->id;
			$notification_id = 1;
			$admin_users = User::where('role_id',1)->get();
			foreach($admin_users as $admin){
				sendNotification($sender_id,$admin->id,$requested_id,$notification_id);
				sendEmailNotification($sender_id,$admin->id,'new_request',$requested_id);
			}
			//sending notification to the super admin about the new request.
			
			
			//-----------Create Audit Log-------------------
				$logData = array();
				$user_id = user_id();
				$userDetails = User::where('id',$user_id)->first();
				$logData['request_id'] = $requestData->case_number;
				$logData['event_log_id'] = get_event_id('new_request');
				$logData['username']   = $userDetails->first_name.' '.$userDetails->last_name;
				$logData['ipaddress']   = get_client_ip();
				$auditData= AuditLog::create($logData);
			//-----------Create Audit Log-------------------

			return Response::json($result, 200);
		} 
		
    }
/*===========================================================
      //SHOW EDIT FORM WITH REQUEST VALUE FROM DB 
==============================================================*/		
  
    public function edit($request_id)
    {
		$requests = RequestCase::where('id',$request_id)->get();
		$request =$requests[0] ;
		if(count($requests)>0){
			$view = view("modal.requestEdit",compact('request'))->render();
			$success = true;
		}else{
			$view = '';
			$success = false;
		}
		
        //abort_unless(\Gate::allows('request_edit'), 403);
		
		return Response::json(array(
		  'success'=>$success,
		  'data'=>$view
		 ), 200);

    } 
/*===========================================================
    UPDATE REQUEST IN DB 
==============================================================*/	
    
    public function update(StoreCaseRequest $request, $request_id)
    {
        //abort_unless(\Gate::allows('request_edit'), 403);

		$requestData = RequestCase::where('id',$request_id);
		//pr($request_id);
	  	if($request->ajax()){
			
			$data =array();
			$data['name']= implode('|',$request->name);
			$social_media = array();
			foreach($request->social_type as $key=>$value){
				$social_media[$value] = $request->social_name[$key];
			}
			$data['social_media'] = json_encode($social_media);
			$data['company'] = implode('|',$request->company);
			$data['url'] = implode('|',$request->url);
			$data['other_info'] = $request->other_info;
			$data['priority'] = $request->priority;
			$data['data_archive'] = implode('|',$request->data_archive);
			
			$requestData->update($data);
			
			$result =array(
			'success' => true,
			'priority'=>ucwords($request->priority)
			);	
			
			
			/*-----------Create Audit Log-------------------*/
				$logData = array();
				$getRequestData = RequestCase::where('id',$request_id)->first();
				$user_id = user_id();
				$userDetails = User::where('id',$user_id)->first();
				$logData['request_id'] = $getRequestData->case_number;
				$logData['event_log_id'] = get_event_id('edit_request');
				$logData['username']   = $userDetails->first_name.' '.$userDetails->last_name;
				$logData['ipaddress']   = get_client_ip();
				$auditData= AuditLog::create($logData);
			/*-----------Create Audit Log-------------------*/
			return Response::json($result, 200);
		}  
    }

/*===========================================================
    SHOW REQUEST DEATILS PAGE BY ID 
==============================================================*/
    public function show($request_id)
    {

		$requests = RequestCase::where('id',$request_id)->get();
		$request =$requests[0] ;
        $roleIdArr = Config::get('constant.role_id');
		
	   /*  if($roleIdArr['DATA_ANALYST']== current_user_role_id()){
		  abort_unless(\Gate::denies(current_user_role_name()), 403);
	    } */
		
		/* $report = Report::where('request_id',$request_id)->get();
		if(count($report)>0){
			 $report[0]->id;
			
		} */
		//$reports_data  = \App\Models\Report::where('request_id',$request_id)->get();
		$reports  = \App\Models\Report::with('attachment','comments')->where('request_id',$request_id)->get();
		
		//pr($reports);
		$comments = array();
		$attachment = array();
		foreach($reports as $key=>$report){
		  $comments = $report->comments;
		  $attachment = $report->attachment;
		}
		
		

         //pr($comments);
        
        return view('users.requests.show', compact('request','roleIdArr','comments','reports'));
    }
	
/*===========================================================
   USER DOWNLOAD REPORT 
==============================================================*/	
	public function donwloadReport($report_id)
    {
	   $report = Report::where('id',$report_id)->get();
	   if(count($report)>0){
		$zipfile_name = $report[0]->zip_file_name;
		$file_path = public_path().'/uploads/reports/'.$report_id ; 

		$filetopath=$file_path.'/'.$zipfile_name;
		
		 $headers = array(
                'Content-Type' => 'application/octet-stream',
            );

        if(file_exists($filetopath)){
			
			//Email the details for zip file
			$email_template  = EmailTemplate::where('template_name',"report_download")->first();
			$requestDetails =  Report::where('id',$report_id)->first();
			$request_id = $requestDetails->request_id;
			$request_details = RequestCase::where('id',$request_id)->first();
			$userDetails = User::where('id',$request_details->requested_user_id)->first();
			$full_name = $userDetails->first_name.' '.$userDetails->last_name;
			$caseNumber = $request_details->case_number;
			$password  = $requestDetails->zip_password;
			$message = $email_template->content;
			$message = str_replace('[NAME]',$full_name,$message);
			$message = str_replace('[CASE_NUMBER]',$caseNumber,$message);
			$message = str_replace('[PASSWORD]',$password,$message);
			$subject = $email_template->subject;
			send_email($userDetails->email,$subject,$message,$from='',$fromname='');
			
			
			/*-----------Create Audit Log-------------------*/
				$logData = array();
				$getRequestData = RequestCase::where('id',$request_id)->first();
				$user_id = user_id();
				$users = User::where('id',$user_id)->first();
				$logData['request_id'] = $request_details->case_number;
				$logData['event_log_id'] = get_event_id('report_download');
				$logData['username']   = $users->first_name.' '.$users->last_name;
				$logData['filename']   = $zipfile_name;
				$logData['ipaddress']   = get_client_ip();
				$auditData= AuditLog::create($logData);
			/*-----------Create Audit Log-------------------*/
			
            return response()->download($filetopath,$zipfile_name,$headers);
        }
		
	   }
    }

/*==================================================
   //ADVNCE FILTER SEARCH 
==================================================*/
	public function advance_search($request)
	{
		
		   
		    $number_of_records =$this->per_page;
			$name = $request->name;
			$case_number = $request->case_number;
			$status = $request->status;
			$priority = $request->priority;
			$start_date = $request->start_date;
			$end_date = $request->end_date;
			//pr($request->all());
			//REQUEST SEARCH START FROM HERE
			
			
			
			$result = RequestCase::where(`1`, '=', `1`);
			$roleIdArr = Config::get('constant.role_id');
			if($name!='' || $case_number!=''|| trim($status)!=''|| trim($priority)!='' || $start_date!= '' || $end_date!=''){
				
				$case_name = '%' . $request->name . '%';
				$case_number_q = '%' . $request->case_number .'%';
				if($start_date!= '' || $end_date!=''){
					if((($start_date!= '' && $end_date=='') || ($start_date== '' && $end_date!='')) || (strtotime($start_date) >= strtotime($end_date))){	
						return  'date_error'; 
					}
				}
				
				$start_date_c = date('Y-m-d',strtotime($start_date));
				$end_date_c= date('Y-m-d',strtotime($end_date));
				
				
				 //CUSTOMER_USER  
				/*  if(current_user_role_id()== $roleIdArr['CUSTOMER_USER']){
					$result = $result->where('requested_user_id', '=', user_id());
				 } */
				 
	
				// check case number 
				if(isset($case_number) && !empty($case_number)){
					$result->where('case_number','LIKE',$case_number_q);
				} 
				// check name 
				if(isset($name) && !empty($name)){
					$result->where('name','LIKE',$case_name);
				}
				// check status 
				if(isset($status) && !empty($status)){
					$result->where('status',$status);
				}
				// check priority 
				if(isset($priority) && !empty($priority)){
					$result->where('priority',$request->priority);
				}
				 // check date and time  
				if(!empty($start_date) &&  !empty($end_date)){
					$result->where(function($q) use ($start_date_c,$end_date_c) {
					$q->whereDate('created_at','>=' ,$start_date_c);
					$q->whereDate('created_at','<=', $end_date_c );
				  });
				} 
				//echo  $result->toSql();
				//$requests = $result->paginate($number_of_records);
				
			   //REQUEST SEARCH START FROM HERE   
			}
			
			    //IF URL REPORTS
				 if($request->url_name == 'reports'){
					 $result = $result->where('status', '=',3);
				 }
				
				 // LISTING SHOW WITHOUT FILTER SELECTED 

				 // DATA_ANALYST
				 if(current_user_role_id()==$roleIdArr['DATA_ANALYST']){
					 $result = $result->where('assigned_user_id', '=', user_id());
				 }
				 //CUSTOMER_ADMIN SHOW ONLY HIS ADDED USER REQUEST AND HIS OWN
				  if(current_user_role_id()==$roleIdArr['CUSTOMER_ADMIN']){
					 $users = User::where('created_by',user_id())->get();
					 $ids =array();
			        if(count($users)>0){
						foreach($users as $key=>$user_id){
							$ids[$key]=$user_id->id;
						}
						$result = $result->whereIn('requested_user_id', $ids);
					 }else{
						$result = $result->where('requested_user_id',user_id()); 
					 }
					  
					 //$requests = RequestCase::paginate($number_of_records);
				 } 
				 //CUSTOMER_USER 
				 if(current_user_role_id()==$roleIdArr['CUSTOMER_USER']){
					 $result = $result->where('requested_user_id', '=', user_id());
				 }	
			 
			 
			 $requests = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
			
			return $requests ;
	}
/*===========================================================
    SHOW ASSIGN REQUEST MODAL TO ASSIGN REQUEST TO ANALYST
==============================================================*/	
	public function requestAssignModal(Request $request)
    {
        $request_id = $request->request_id;
		$roleIdArr  = Config::get('constant.role_id');
		$analyst    = User::where('role_id',$roleIdArr['DATA_ANALYST'])->get();
		//pr($analyst);
		 if(count($analyst)>0){
			$view = view("modal.requestAssign",compact('request_id','analyst'))->render();
			$success = true;
		}else{
			$view = '';
			$success = false;
		} 
		
        //abort_unless(\Gate::allows('request_edit'), 403);
		
		return Response::json(array(
		  'success'=>$success,
		  'data'=>$view
		 ), 200);
    }
	
/*===============================================
     ASSIGN REQUEST TO ANALYST 
=================================================*/
	public function requestAssignToAnalyst(Request $request)
    {
	
        $requests = RequestCase::where('id',$request->request_id)->first();
		
		$assigned_to = user_data_by_id($request->user_id);
		$assigned_By = user_data_by_id(user_id());   
		
		$assigned_to_name = $assigned_to->first_name .' '. $assigned_to->last_name;
		$assigned_By_name = $assigned_By->first_name .' '. $assigned_By->last_name;
		
		
		//Status 
		$data = get_request_status_name(1);
		$old_status= $data['status'];
		$old_class= $data['cls'];
		
		$data = get_request_status_name(2);
		$new_status= $data['status'];
		$new_class= $data['cls'];
		$requests_update = RequestCase::where('id',$request->request_id);	
	    $data=array();
		$data['assigned_user_id']= $request->user_id;
		$data['assignedBy']= user_id();
		$data['status']= 2;
		//UPDATE REQUEST 
		$requests_update->update($data);  
		$requested_id  = $request->request_id;
		
		//sending notification to the data analyst and user who created the request
		sendNotification(user_id(),$request->user_id,$requested_id,$notification_id=2);
		sendNotification(user_id(),$requests->requested_user_id,$requested_id,$notification_id=3);
		/* sendEmailNotification(user_id(),$request->user_id,'new_request_assign');
		sendEmailNotification(user_id(),$requests->requested_user_id,'data_analyst_assign'); */
		//sending notification to the data analyst and user who created the request
		//sendEmailNotification($requests->requested_user_id);
		sendEmailNotification(user_id(),$request->user_id,'assign_request_data_analyst',$request->request_id);
		
		 return Response::json(array(
		  'success'=>true,
		  'assigned_to'=>$assigned_to_name,
		  'assigned_by'=>$assigned_By_name,
		  'old_class'=>$old_class,
		  'new_class'=>$new_class,
		  'new_status'=>$new_status,
		  
		 ), 200);  
    }
	
/*===========================================================
    SHOW CLASRIFICATION  MODAL TO ASK FOR CLARIFICATION TO ANALYST
==============================================================*/	
	public function clarificationModal(Request $request)
    {
        $request_id = $request->request_id;
		$roleIdArr  = Config::get('constant.role_id');
		$report    = Report::where('request_id',$request_id)->get();
		//pr($analyst);
		 if(count($report)>0){
			$report=$report[0];
			$view = view("modal.clarificationModal",compact('request_id','report'))->render();
			$success = true;
		}else{
			$view = '';
			$success = false;
		} 
		
        //abort_unless(\Gate::allows('request_edit'), 403);
		
		return Response::json(array(
		  'success'=>$success,
		  'data'=>$view
		 ), 200);
    }
	
/*===========================================================
   ASK FOR CLARIFICATION 
==============================================================*/	
	public function clarificationRequest(ClarificationRequest $request)
    {
		
        $request_id = $request->request_id;
		$request_data    = RequestCase::where('id',$request_id)->get();
        $report_id = $request->report_id;
		
		$request_data[0]->assigned_user_id;
		$data_update    = RequestCase::where('id',$request_id);
		$request_data_update =array();
		$request_data_update['status']=4;
		$request_data_update['completed_at']=NULL;
	
	    $data_update->update($request_data_update);
		
		$comment =array();
		$comment['comment'] = $request->clarification;
		$comment['sender_id'] = user_id();
		$comment['reciever_id'] = $request_data[0]->assigned_user_id;
		$comment['report_id'] = $report_id;
		$comment['request_status'] = 4;
		$comment['create_at']=date('Y-m-d H:i:s');
		$comment['updated_at']=date('Y-m-d H:i:s');
		$comment_data =  Comment::create($comment);

		//Old completed before update the status  	
		$data1 = get_request_status_name($request_data[0]->status);
		$remove_cls= $data1['cls'];
		//REOPENED STATUS -4
		$data = get_request_status_name(4);
		$status= $data['status'];
		$status_cls= $data['cls'];
		
		/// send notification to data analyst and super admin that request case has been reopened
		$requested_data  = RequestCase::where('id',$request_id)->first();
		$requested_id = $request_id;
		sendNotification(user_id(),$requested_data->assigned_user_id,$requested_id,$notification_id=6);
		sendNotification(user_id(),$requested_data->assignedBy,$requested_id,$notification_id=7);
		/// send notification to data analyst and super admin that request case has been reopened
		/* sendEmailNotification(user_id(),$requested_data->assigned_user_id,'reopen_request_data_analyst');
		sendEmailNotification(user_id(),$requested_data->assignedBy,'reopen_request_super_admin'); */
		
		
		/*-----------Create Audit Log-------------------*/
			$logData = array();
			$getRequestData = RequestCase::where('id',$request_id)->first();
			$user_id = user_id();
			$users = User::where('id',$user_id)->first();
			$logData['request_id'] 	= $getRequestData->case_number;
			$logData['event_log_id'] 	= get_event_id('request_reopened');
			$logData['comment'] 	= $request->clarification;
			$logData['username']   	= $users->first_name.' '.$users->last_name;
			$logData['ipaddress']   = get_client_ip();
			$auditData= AuditLog::create($logData);
		/*-----------Create Audit Log-------------------*/
		
		return Response::json([
			'success' => true,
			'status'=>$status,
			'status_cls'=>$status_cls,
			'remove_cls'=>$remove_cls,
			'completed_at'=>'-',

		], 200);
		
    }
	
	/*===========================================================
   View DOWNLOAD REPORT FOR ADMIN
==============================================================*/	
	public function viewDonwloadReport($report_id,$attachment_id)
    {
	   $attachment = RequestAttachment::where('id',$attachment_id)->first();
	   if($attachment){
		$file_name = $attachment->filename;
		$file_path = public_path().'/uploads/reports/'.$report_id;
		$filetopath=$file_path.'/'.$file_name;
		 $headers = array(
                'Content-Type' => 'application/octet-stream',
            );
        if(file_exists($filetopath)){
			$requestDetails =  Report::where('id',$report_id)->first();
			$request_id = $requestDetails->request_id;
			/*-----------Create Audit Log-------------------*/
			$request_details = RequestCase::where('id',$request_id)->first();
					$logData = array();
				$getRequestData = RequestCase::where('id',$request_id)->first();
				$user_id = user_id();
				$users = User::where('id',$user_id)->first();
				$logData['request_id'] = $request_details->case_number;
				$logData['event_log_id'] = get_event_id('report_download');
				$logData['username']   = $users->first_name.' '.$users->last_name;
				$logData['filename']   = $file_name;
				$logData['ipaddress']   = get_client_ip();
				$auditData= AuditLog::create($logData);
			/*-----------Create Audit Log-------------------*/
            return response()->download($filetopath,$file_name,$headers);
        }
	   }
    }

/*
    public function destroy(RequestCase $request)
    {
        abort_unless(\Gate::allows('request_delete'), 403);

        $request->delete();

        return back();
    }

    public function massDestroy(MassDestroyRequestRequest $request)
    {
        Product::whereIn('id', $request('ids'))->delete();

        return response(null, 204);
    } */
}
