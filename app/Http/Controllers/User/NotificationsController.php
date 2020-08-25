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
use App\Models\Notification;
use App\Models\NotificationMessage;
use Config;
use Illuminate\Support\Str;
use Response;
use ZipArchive;
use File;
class NotificationsController extends Controller
{

	public function __construct()
    {
	    
    }
	public function index(Request $request){
		
		$user_id = user_id();
		$notifications = Notification::where('reciever_id',$user_id)->with(['notificationMessage','requests','sender'])->orderBy('created_at', 'desc')->get();
		//pr($notifications->toArray());die;
        return view('users.notifications.index',compact('notifications'));
		
	}
	
	public function getUnreadNotificationsCount(){
		$user_id = user_id();
		$notifications = Notification::where('reciever_id',$user_id)->where('status','0')->with(['notificationMessage','requests','sender'])->get();
		
		$data = array();
		if($notifications && count($notifications) > 0){
			
			$notes = "";
			$data['success'] = true;
			$notification_count = count($notifications); 
			$data['total_notification'] = $notification_count;
			
		}else{
			$data['success'] = false;
			$data['total_notification'] = 0;
			
		}
		echo json_encode($data);die;
		
	}
	
	public function getUnreadNotifications(){
		$user_id = user_id();
		$roleIdArr = Config::get('constant.role_id');
		$notifications = Notification::where('reciever_id',$user_id)->with(['notificationMessage','requests','sender'])->orderBy('created_at', 'desc')->limit(3)->get();
		
		$notifications_count = Notification::where('reciever_id',$user_id)->with(['notificationMessage','requests','sender'])->get();
		
		$data = array();
		if($notifications && count($notifications) > 0){
			
			$notes = "";
			$data['success'] = true;
			$notification_count = count($notifications_count); 
			$url_name = 'report';

			if($roleIdArr['CUSTOMER_USER']==current_user_role_id() || $roleIdArr['CUSTOMER_ADMIN']==current_user_role_id()){
				$url_name = 'show';
			}
			foreach($notifications as $key=>$notification){
				$request_case = RequestCase::where('id',$notification->requested_id)->select('case_number')->first();
				
				$notes .= '<div class="d-flex flex-row mb-3 pb-3 border-bottom ">
									<div>
										<a href="'.url('/requests/'.$url_name.'/'.$notification->requested_id).'" class="readNotification">
											<p class="font-weight-medium mb-1">'.str_replace('[case_number]',$request_case->case_number,$notification->notificationMessage->notification_msg).'</p>
											<p class="text-muted mb-0 text-small">'.date("Y-m-d h:i",strtotime($notification->created_at)).'</p>
										</a>
									</div>
								</div>';
			}
			$data['notifications'] = $notes;
			$data['total_notification'] = $notification_count;
			
		}else{
			$data['success'] = false;
		}
		$this->updateNotificationStatus();
		echo json_encode($data);die;
		
	}
	
	public function updateNotificationStatus(){
		$user_id = user_id();
		$notifications = Notification::where('reciever_id',$user_id)->where('status','0');
		$data = array();
		$data['status'] = 1;
		$notifications->update($data);
		
	}
}
?>	