<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAnalystReoprt;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\RequestCase;
use App\Models\Report;
use App\Models\RequestAttachment;
use App\Http\Requests\ClarificationRequest;
use App\Models\Comment;
use App\Models\User;
use Config;
use Illuminate\Support\Str;
use Response;
use ZipArchive;
use File;
use App\Models\AuditLog;
class RequestsReplyController extends Controller
{
	protected $per_page;
	private $photos_path;
	public function __construct()
    {
	    
        $this->per_page = Config::get('constant.per_page');
		$this->report_path = public_path('/uploads/reports');
    }
    public function ajaxPagination(Request $request)
    {
		
			$roleIdArr = Config::get('constant.role_id');
	
			$number_of_records =$this->per_page;
			$requests = RequestCase::where(`1`,`1`);
		

			 // DATA_ANALYST
			 if(current_user_role_id()== $roleIdArr['DATA_ANALYST']){
				 $requests = $requests->where('assigned_user_id', '=', user_id());
				 
			 }
			 
			 //CUSTOMER_ADMIN SHOW ONLY HIS ADDED USER REQUEST AND HIS OWN
			  if(current_user_role_id()==$roleIdArr['CUSTOMER_ADMIN']){
				 $users = User::where('created_by',user_id())->get();
				 $ids =array();
				if(count($users)>0){
					foreach($users as $key=>$user_id){
						$ids[$key]=$user_id->id;
					}
					$requests = $requests->whereIn('requested_user_id', $ids);
				 }else{
					$requests = $requests->where('requested_user_id',user_id()); 
				 }
			 } 
			 //CUSTOMER_USER
			 if(current_user_role_id()==$roleIdArr['CUSTOMER_USER']){
				$requests = $requests->where('requested_user_id', '=', user_id());
			 }

            $requests = $requests->orderBy('created_at', 'desc')->paginate($number_of_records);

			if ($request->ajax()) {
				return view('users.requests.requestPagination', compact('requests'));
			} 
			return view('users.requests.index',compact('requests'));
		
		   
    }




/*===========================================================
    OPEN REPORT FORM BY REQUEST ID 
==============================================================*/
    public function reportForm($request_id)
    {
		
		$roleIdArr = Config::get('constant.role_id');
		
		
		if($roleIdArr['CUSTOMER_ADMIN']== current_user_role_id() || $roleIdArr['CUSTOMER_USER']== current_user_role_id()){
		  abort_unless(\Gate::denies(current_user_role_name()), 403);
	    } 
		

		$requests = RequestCase::where('id',$request_id)->get();
		$request =$requests[0] ;
        $roleIdArr = Config::get('constant.role_id');
		
	   
		$reports  = \App\Models\Report::with('attachment','comments')->where('request_id',$request_id)->get();
		
		//pr($reports);
		$comments = array();
		$attachment = array();
		foreach($reports as $key=>$report){
		  $comments = $report->comments;
		  $attachment = $report->attachment;
		}

        // pr($attachment);
        
        return view('users.requests.request_report', compact('request','roleIdArr','comments','attachment','reports'));
    }


/*===============================================
       ANALYST REPORT 
==============================================*/
	public function anlystReportSubmit(Request $report)
    {
		
		//pr($report_file = $report->file('file'));
		
        $request_id = $report->request_id;
		$comment_txt = $report->comment;
        $report_data = Report::where('request_id',$request_id)->get();
	
		if(count($report_data)<=0){
			//INSERT REPORT 
			$report1 = Report::create(array('request_id'=>$request_id,'created_at'=>date('Y-m-d H:i:s')));
			$report_id= $report1->id;
		}else{
			$report_id= $report_data[0]->id;
		}
		
		//IF ROLE IS ADMIN THEN UPDDATE IN REPORT CHANGED BY FIELD IN REQUEST TABLE 
		$user_id=null;
		$request_status_data =array();
		$admin_changed_in_reports = false;
		$roleIdArr = Config::get('constant.role_id');
		$request_data = RequestCase::where('id',$request_id)->get();
		//IF DATA ADMIN UPLOAD THEN CHNAGE STATUS 'REOPENED'
		if($roleIdArr['DATA_ADMIN']== current_user_role_id() && $request_data[0]->status==3){
			$user_id = user_id();
			$admin_changed_in_reports = true;
			$request_status_data['status'] = 4;
		    $request_status_data['completed_at'] = NULL; 
		} 
		$request_updated_status   = RequestCase::where('id',$request_id);
		$request_status_data['status_changed_by'] = $user_id;
		$request_updated_status->update($request_status_data); 
		
		
		
		//COMMENT ADDED 
		$comment = array();
		$comment['report_id']=$report_id;
		$comment['sender_id']=user_id();
		$comment['reciever_id']=$request_data[0]->requested_user_id;
		$comment['request_status']=$request_data[0]->status;
		$comment['create_at']=date('Y-m-d H:i:s');
		$comment['updated_at']=date('Y-m-d H:i:s');
		$comment['comment']=$comment_txt;
		$comment_data =  Comment::create($comment);
	
		
		
		/*-----------Create Audit Log-------------------*/
		$logData = array();
		$getRequestData = RequestCase::where('id',$request_id)->first();
		$user_id = user_id();
		$users = User::where('id',$user_id)->first();
		$logData['request_id'] = $getRequestData->case_number;
		$logData['event_log_id'] = get_event_id('comment_update');
		$logData['username']   = $users->first_name.' '.$users->last_name;
		$logData['comment']   = $comment_txt;
		$logData['ipaddress']   = get_client_ip();
		$auditData= AuditLog::create($logData);
		/*-----------Create Audit Log-------------------*/
		
		
		
		//FILE UPLOAD DATA 
		$report_file = $report->file('file');

         if (!is_array($report_file)) {
            $report_file = [$report_file];
        }
        //CREATE REPORT FOLDER IF NOT 
        if (!is_dir($this->report_path)) {
            mkdir($this->report_path, 0777);
        }
		//CREATE REPORT ID FOLDER 
		$report_path = $this->report_path.'/'.$report_id;
		if (!is_dir($report_path)) {
            mkdir($report_path, 0777);
        }
		
		// Crate Zip File with password protected 
		/* $zip = new ZipArchive;
		$zip_filename = $request_data[0]->case_number .'.zip';
        $zip_filename_path = $report_path.'/'.$zip_filename;
		$zip->open($zip_filename_path, ZipArchive::CREATE);
		$password = getToken(10);
		$zip->setPassword($password); */
		
		//UPDATE THE REPORT TABLE 
		/* $update_reported_data = Report::where('id',$report_id);
		$report_data_to_update=array();
		$report_data_to_update['zip_file_name']=$zip_filename;
		$report_data_to_update['zip_password']=$password;
		$update_reported_data->update($report_data_to_update); */
		
		//ARRAY OF UPLOADED FILES 
		$download_rows ='';
		$file_list =array();
        for ($i = 0; $i < count($report_file); $i++) {
            $photo = $report_file[$i];
            $name = sha1(date('YmdHis') . Str::random(30));
            $save_name = $name . '.' . $photo->getClientOriginalExtension();
			
			//MOVE IMAGE TO REPORT ID FOLDER 
            $photo->move($report_path, $save_name);
		
		    //ADD ATTACHMENT FILES DATA
			$attachment_data = array();
			$original_name = basename($photo->getClientOriginalName());
			$attachment_data['report_id']=$report_id;
			$attachment_data['filename']=$save_name;
			$attachment_data['original_name']=$original_name;
		
			$attache_data =RequestAttachment::create($attachment_data);
			
			//ADD FILES TO ZIP FOLDER 
			/* $file_path = $report_path.'/'.$save_name;
			$zip->addFile($file_path,$save_name);
			$zip->setEncryptionName($save_name, ZipArchive::EM_AES_256); */
			
			//For Audit Log 
			$file_list['file_'.$i] =  $save_name;
			
			$download_rows .= '<tr id="attachment_id_'.$attache_data->id .'"><td>';
			$download_rows .= pathinfo($original_name, PATHINFO_FILENAME);
			$download_rows .='</td><td>';
			$download_rows .= pathinfo($original_name, PATHINFO_EXTENSION);
			$download_rows .='</td><td>';
			$download_rows .= \Carbon\Carbon::parse($attache_data->created_at)->format('d M, Y H:i');
			$download_rows .='</td>';
			$download_rows .='<td class="remove_column" id="open_confirmBox" data-confirm_message ="Are you want to Delete the file ?"  data-left_button_name ="Delete" data-left_button_cls="btn-danger" data-left_button_id ="delete_file"  data-id="'.$attache_data->id .'"><a href="javascript:void(0)"> <i class="glyph-icon simple-icon-trash"></i></a></td></tr>';
			
			
        }
		
		/*-----------Create Audit Log-------------------*/
		if(!empty($file_list)){
		$logData = array();
		$getRequestData = RequestCase::where('id',$request_id)->first();
		$user_id = user_id();
		$users = User::where('id',$user_id)->first();
		$logData['request_id'] 	= $getRequestData->case_number;
		$logData['event_log_id'] 	= get_event_id('file_upload');
		$logData['username']   	= $users->first_name.' '.$users->last_name;
		$logData['filename']   	= json_encode($file_list);
		$logData['ipaddress']   = get_client_ip();
		$auditData= AuditLog::create($logData);
		}
		/*-----------Create Audit Log-------------------*/
		//$zip->close(); 
        $request_data_new = RequestCase::where('id',$request_id)->get();
		//IF ADMIN AND STATUS CHANGED IS NOT NULL 
		if($admin_changed_in_reports  && $request_data_new[0]->status_changed_by!=NULL){
		//Old completed before update the status  	
		$data1 = get_request_status_name($request_data[0]->status);
		$remove_cls= $data1['cls'];
		//NEW STATUS
		$data = get_request_status_name($request_data_new[0]->status);
		$status= $data['status'];
		$status_cls= $data['cls'];
		
		
		//sending notification to the data analyst and user who created the request
		$requested_data  = RequestCase::where('id',$request_id)->first();
		$requested_id = $request_id;
		sendNotification(user_id(),$requested_data->requested_user_id,$requested_id,$notification_id=8);
		sendNotification(user_id(),$requested_data->assigned_user_id,$requested_id,$notification_id=9);
		//sending notification to the data analyst and user who created the request
		/* sendEmailNotification(user_id(),$requested_data->assigned_user_id,'request_reopen_data_analyst');
		sendEmailNotification(user_id(),$requested_data->assignedBy,'request_reopen_customer_user'); */
		
		/*-----------Create Audit Log-------------------*/
		$logData = array();
		$getRequestData = RequestCase::where('id',$request_id)->first();
		$user_id = user_id();
		$users = User::where('id',$user_id)->first();
		$logData['request_id'] 	= $getRequestData->case_number;
		//$logData['comment'] 	= $comment_txt;
		$logData['event_log_id'] 	= get_event_id('request_reopened');
		$logData['username']   	= $users->first_name.' '.$users->last_name;
		$logData['ipaddress']   = get_client_ip();
		$auditData= AuditLog::create($logData);
		/*-----------Create Audit Log-------------------*/
		
		return Response::json([
            'success' => true,
			'status'=>$status,
			'status_cls'=>$status_cls,
			'remove_cls'=>$remove_cls,
			'admin'=>$admin_changed_in_reports,
			'attachment_files'=>$download_rows,
        ], 200);
		
		}else{
        return Response::json([
            'success' => true,
			'attachment_files'=>$download_rows,
			'admin'=>$admin_changed_in_reports,
        ], 200);
		}
		
      
    }
/*===============================================
      WHEN COMPELTE THE REPROT 
	  * UPDATE COMPLETE DATE/STATUS
	  * IF ADMIN CHANGE status_changed_by fields 
==============================================*/	
	public function completeReport(Request $request)
    {
      
        $roleIdArr = Config::get('constant.role_id');
		$user_id=null;
		$admin = false;
		//IF ROLE IS ADMIN THEN UPDDATE STATUS CHANGED BY FIELD IN REQUEST TABLE 
		if($roleIdArr['DATA_ADMIN']== current_user_role_id()){
			//$user_id = user_id();
			$admin = true;
		}
		$request_id = $request->request_id;
		
		$report_data  = \App\Models\Report::with('attachment','comments')->where('request_id',$request_id)->get();
		
		//pr(count($report_data[0]->comments));
	
		// IF NO COMMENTS AND FILES IN THE REPORT THEN NOT COMPLETE THE REPROT 
		if(count($report_data[0]->comments)>0 || count($report_data[0]->attachment)>0){
		 
        //$report_data = Report::where('request_id',$request_id)->get();
		
		$report_id= $report_data[0]->id;
	
		$RequestAttachment = RequestAttachment::where('report_id',$report_id)->get();
		
		// Crate Zip File with password protected 
		$request_data = RequestCase::where('id',$request_id)->get();
		$report_path = $this->report_path.'/'.$report_id;
		$zip = new ZipArchive;
		$zip_filename = $request_data[0]->case_number .'.zip';
        $zip_filename_path = $report_path.'/'.$zip_filename;
		$zip->open($zip_filename_path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		$password = getToken(10);
		$zip->setPassword($password);
		
		
	    foreach($RequestAttachment as $key =>$attachment){

			//ADD FILES TO ZIP FOLDER 
			$file_path = $report_path.'/'.$attachment->filename;
			$zip->addFile($file_path,$attachment->filename);
			$zip->setEncryptionName($attachment->filename, ZipArchive::EM_AES_256);
		}
		$zip->close(); 
		//UPDATE THE REPORT TABLE 
		$update_reported_data = Report::where('id',$report_id);
		$report_data_to_update=array();
		$report_data_to_update['zip_file_name']=$zip_filename;
		$report_data_to_update['zip_password']=$password;
		$report_data_to_update['created_at'] = date('Y-m-d H:i:s');
		$update_reported_data->update($report_data_to_update);
		
		//IF REQUEST STATUS IS COMPLETED UPDATE STATUS TO COMPLETE AND DATE ALSO 
		$request_updated_status  = RequestCase::where('id',$request_id);
		$request_status_data = array();
		$request_status_data['status'] = 3;
		$request_status_data['completed_at'] = date('Y-m-d H:i:s');
		//$request_status_data['status_changed_by'] = $user_id;
		$request_updated_status->update($request_status_data); 
		
		//UPDATE COMMENT TABLE STATUS 
		$cmt = Comment::where('report_id',$report_id)->take(1)->orderBy('id', 'desc')->get();
		$comment =Comment::where('id',$cmt[0]->id);
		$comment_data['request_status']=3;
		$comment_data['created_at']=$cmt[0]->created_at;
		$comment_data['updated_at']=$cmt[0]->updated_at;
		$comment->update($comment_data);
	
		
		//EXISTING STATUS 
		$data1 = get_request_status_name($request_data[0]->status);
		$remove_cls= $data1['cls'];
		//NEW STATUS
		$data = get_request_status_name(3);
		$status= $data['status'];
		$status_cls= $data['cls'];
		
		$date = \Carbon\Carbon::parse(date('Y-m-d H:i:s'))->format('d-M-Y');
		
		//sending notification to the data analyst and user who created the request
		$requested_data  = RequestCase::where('id',$request_id)->first();
		$requested_id = $request_id;
		if($admin){
			sendNotification(user_id(),$requested_data->requested_user_id,$requested_id,$notification_id=4);
			sendNotification(user_id(),$requested_data->assigned_user_id,$requested_id,$notification_id=5);
			sendEmailNotification(user_id(),$requested_data->requested_user_id,'complete_request_by_super_admin',$requested_id);
			sendEmailNotification(user_id(),$requested_data->assigned_user_id,'complete_request_data_admin_by_super_admin',$requested_id);
		}else{
			sendNotification(user_id(),$requested_data->requested_user_id,$requested_id,$notification_id=10);
			sendNotification(user_id(),$requested_data->assignedBy,$requested_id,$notification_id=11);
			sendEmailNotification(user_id(),$requested_data->requested_user_id,'complete_request_super_admin_by_data_admin',$requested_id);
			sendEmailNotification(user_id(),$requested_data->assignedBy,'complete_request_super_admin_by_data_admin',$requested_id);
		}
		//sending notification to the data analyst and user who created the request
		
		/*-----------Create Audit Log-------------------*/
		$logData = array();
		$logData['request_id'] = $requested_data->case_number;
		$logData['event_log_id'] = get_event_id('complete_request');
		if($admin){
			$logData['username']   = "Admin";
		}else{
			$logData['username']   = "Data Admin";
		}
		$logData['ipaddress']   = get_client_ip();
		$auditData= AuditLog::create($logData);
		/*-----------Create Audit Log-------------------*/
		 return Response::json([
            'success' => true,
			'admin'=>$admin,
			'status'=>$status,
			'status_cls'=>$status_cls,
			'remove_cls'=>$remove_cls,
			'completed_at'=>$date,
        ], 200); 
	    }else{
		
		 return Response::json([
            'success' => false,	
        ], 200); 
		 }
		
       
      
    }
	
/*===============================================
     DELETE REPROT FILE 
==============================================*/	
	public function DeleteReportFile(Request $attachment)
	{
	  
	 if ($attachment->ajax()) {
		$attachment_id = $attachment->attachment_id;
		$data = RequestAttachment::where('id', $attachment_id)->get();
		if(count($data)>0){
			$file_path = $this->report_path .'/'.$data[0]->report_id .'/'.$data[0]->filename;
				//UNLINK FILE FROM FOLDER 
				@unlink($file_path);
				//DELTE FILE FROM DB 
		    	RequestAttachment::where('id', $attachment_id)->delete();
	
				$report =Report::where('id',$data[0]->report_id)->get();
				$request_id= $report[0]->request_id;
				$user_id=null;
				$request_status_data =array();
				$admin_changed_in_reports = false;
				$roleIdArr = Config::get('constant.role_id');
				$request_data = RequestCase::where('id',$request_id)->get();
				//IF DATA ADMIN UPLOAD THEN CHNAGE STATUS 'REOPENED'
				if($roleIdArr['DATA_ADMIN']== current_user_role_id() && $request_data[0]->status==3){
					$user_id = user_id();
					$admin_changed_in_reports = true;
					$request_status_data['status'] = 4;
					$request_status_data['completed_at'] = NULL; 
					$request_updated_status   = RequestCase::where('id',$request_id);
					$request_status_data['status_changed_by'] = $user_id;
					$request_updated_status->update($request_status_data); 
					
					// reopen the request if the report is deleted by the admin.
					$requested_data  = RequestCase::where('id',$request_id)->first();
					$requested_id = $request_id;
					sendNotification(user_id(),$requested_data->requested_user_id,$requested_id,$notification_id=8);
					sendNotification(user_id(),$requested_data->assigned_user_id,$requested_id,$notification_id=9);
					// reopen the request if the report is deleted by the admin.
				} 
			   //IF ADMIN AND STATUS CHANGED IS NOT NULL 
				if($admin_changed_in_reports  && $request_data[0]->status_changed_by!=NULL){
				//Old completed before update the status  	
				$data1 = get_request_status_name($request_data[0]->status);
				$remove_cls= $data1['cls'];
				//REOPENED STATUS -4
				$data = get_request_status_name(4);
				$status= $data['status'];
				$status_cls= $data['cls'];
				
				
				
				return Response::json([
					'success' => true,
					'status'=>$status,
					'status_cls'=>$status_cls,
					'remove_cls'=>$remove_cls,
					'admin'=>$admin_changed_in_reports,
					'attachment_id'=>$attachment_id,
				], 200);
				
				}else{
					 return Response::json([
					'success' => true,
					'attachment_id'=>$attachment_id,
				], 200); 
				}
			}
		} 
	}
/*===============================================
    STATUS CHANGE TO REOPENED AND AUTOASSIGN TO ALREADY ASIGNED ALALYST 
==============================================*/	
	public function changeStatus(Request $request)
	{
	  
	 if ($request->ajax()) {
		$request_id = $request->request_id;
		$request_data = RequestCase::where('id',$request_id)->get();
		if(count($request_data)>0){
			
				$user_id=null;
				$request_status_data =array();
				$admin_changed_in_reports = false;
				$roleIdArr = Config::get('constant.role_id');
				$request_data = RequestCase::where('id',$request_id)->get();
				//IF DATA ADMIN UPLOAD THEN CHNAGE STATUS 'REOPENED'
				
				if($roleIdArr['DATA_ADMIN']== current_user_role_id() && $request_data[0]->status==3){
					$user_id = user_id();
					$admin_changed_in_reports = true;
					$request_status_data['status'] = 4;
					$request_status_data['completed_at'] = NULL; 
					$request_updated_status   = RequestCase::where('id',$request_id);
					//$request_status_data['status_changed_by'] = $user_id;
					$request_updated_status->update($request_status_data); 
				
					//Old completed before update the status  	
					$data1 = get_request_status_name($request_data[0]->status);
					$remove_cls= $data1['cls'];
					//REOPENED STATUS -4
					$data = get_request_status_name(4);
					$status= $data['status'];
					$status_cls= $data['cls'];
					
					// reopen the request if the report is deleted by the admin.
					$requested_data  = RequestCase::where('id',$request_id)->first();
					$requested_id = $request_id;
					sendNotification(user_id(),$requested_data->requested_user_id,$requested_id,$notification_id=8);
					sendNotification(user_id(),$requested_data->assigned_user_id,$requested_id,$notification_id=9);
					// reopen the request if the report is deleted by the admin.3
					
					return Response::json([
						'success' => true,
						'status'=>$status,
						'status_cls'=>$status_cls,
						'remove_cls'=>$remove_cls,
						'admin'=>$admin_changed_in_reports,

					], 200);
				
				}else{
					 return Response::json([
					'success' => true
				], 200); 
				}
			}
		} 
	}




/*===========================================================
    SHOW CLASRIFICATION  MODAL TO ASK FOR CLARIFICATION TO ANALYST
==============================================================*/	
	public function showComment(Request $request)
    {
        $comment_id = $request->comment_id;
		$roleIdArr  = Config::get('constant.role_id');
		$comment    = Comment::where('id',$comment_id)->get();
		//pr($comment);
		 if(count($comment)>0){
			$comment=$comment[0];
			$view = view("modal.EditCommentModal",compact('comment'))->render();
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
	public function ModifyComment(ClarificationRequest $request)
    {
		
        $comment_id = $request->comment_id;
		
		$comment_data    = Comment::where('id',$comment_id)->get();
        //$report_id = $request->report_id;
		
		$data_update  = Comment::where('id',$comment_id);
		$request_data_update = array();
		$request_data_update['comment']= $request->clarification;
		$request_data_update['created_at']= $comment_data[0]->created_at;
		$request_data_update['updated_at']= date('Y-m_d H:i:s');
	    $data_update->update($request_data_update);

		
		return Response::json([
			'success' => true,
			'comment'=>$request->clarification,
		], 200);
		
    }
	
	
}

