<?php
//use DB;

//namespace App\Http\Middleware;
use App\Models\Role;
use App\Models\RequestCase;
use App\Models\User;
use App\Models\Setting;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Notification;
use App\Models\EmailTemplate;
use App\Models\EventLog;
use App\Models\AuditLog;
use App\Models\Plan;
//use Config;
// Return User Role ID 
function current_user_role_id(){
	$user = \Auth::user();
	return $user->role_id;
}

function current_user_role_name(){
	$user = \Auth::user();
	$role = Role::where('id',$user->role_id)->get();
	return $role[0]->slug;
} 
/* Get Loggedin User data */
function user_data(){
	$user = \Auth::user();
	return $user;
}

/* Get Current User ID */
function user_id(){
	$user = \Auth::user();
	return $user->id;
}

/* Get User data by ID  */
function user_data_by_id($id){
	$userData = User::where('id',$id)->get();
	return $userData[0];
}

/* Explode by  */
function explodeTo($sign,$data){
	$exp = explode($sign,$data);
	return $exp;
}


function role_data_by_id($id){
	$role = Role::where('id',$id)->get();
	return $role[0];
} 


/*
1-> NEW
2-> PENDING
3->COMPLETED
4->REOPENED

Return: status number
*/
function get_request_status_number($request_id){

        $request = RequestCase::select('status')->where('id',$request_id)->get();
		//pr($request);
		return $request[0]->status;
}

/*
1-> NEW
2-> PENDING
3->COMPLETED
4->REOPENED
Return :Array of class and name of status 
*/
function get_request_status_name($status_number){
		$statusArray =array();
		//pr($request);
		if($status_number==1) { $statusArray['status'] =trans('global.new'); $statusArray['cls'] ='bg-dark';}
		if($status_number==2) { $statusArray['status'] =trans('global.in_progress'); $statusArray['cls'] ='bg-warning';};
		if($status_number==3) { $statusArray['status'] =trans('global.completed'); $statusArray['cls'] ='bg-success';};
		if($status_number==4) { $statusArray['status'] =trans('global.reopened'); $statusArray['cls'] ='bg-dark';};
		return $statusArray;
}

/* Exploade by |  */ 
function split_to_array($sign,$data){
		$data = explode($sign,$data);
		return $data;
}

/* ================================
   If double authentication not set then redirect to below routes of user role base 
============================*/
function redirect_route_name(){
	
	  $role_id = Config::get('constant.role_id');
	  $user_id =user_id();
	  $user_data = user_data_by_id($user_id);

	  if(is_null($user_data->otp)){
		  
	   // IF DATA_ADMIN/DATA_ANALYST/CUSTOMER_USER/CUSTOMER_ADMIN 
	   
	   if($role_id['DATA_ADMIN']== current_user_role_id()){
			return 'users'; 
	   }
	   else if($role_id['DATA_ANALYST']== current_user_role_id()){
			return 'business-users';					
	   }else if($role_id['CUSTOMER_ADMIN']== current_user_role_id()){
			return 'requests'; 

	   }
	   else if($role_id['CUSTOMER_USER']== current_user_role_id()){
			return 'requests'; 
	   }
	   	  
	   }else{
		    \Auth::logout();
		   return 'login'; 
	  }  
}

// USER/ANALYST NOT ALBE TO ACCESS 
function access_denied_user(){
	
		$role_id = Config::get('constant.role_id');
	    if($role_id['CUSTOMER_USER']== current_user_role_id()){
		  return abort_unless(\Gate::denies(current_user_role_name()), 403);
	    } 
}

function access_denied_user_analyst(){
	
		$role_id = Config::get('constant.role_id');
	    if($role_id['CUSTOMER_USER']== current_user_role_id() || $role_id['DATA_ANALYST']== current_user_role_id()){
		  return abort_unless(\Gate::denies(current_user_role_name()), 403);
	    } 
	
}

function user_current_plan($stripe_plan){
	
		$plan_data = Plan::where('stripe_plan',$stripe_plan)->get();
		
		return $plan_data[0];
	
}

//EMAIL SEND 
 function send_email($to='',$subject='',$message='',$from='',$fromname=''){
	try {	
			$mail = new PHPMailer();
			$mail->isSMTP(); // tell to use smtp
			$mail->CharSet = "utf-8"; // set charset to utf8
			
			$setting = Setting::where('id',1)->get();
	
			$mail->SMTPAuth = true;
			$mail->Host = $setting[0]->smtp_host;
			$mail->Port = $setting[0]->smtp_port;
			$mail->Username =$setting[0]->smtp_user;
            $mail->Password = urlsafe_b64decode($setting[0]->smtp_password); 		
			/* $mail->Host = "webethicssolutions.com";
			$mail->Port =587;
			$mail->Username = "php@webethicssolutions.com";
			$mail->Password = "el*cBt#TuRH^"; */
			  
			  //Client SMTP 
			/* $mail->Host = "mail.mgdsw.info";
			$mail->Port =587;
			$mail->Username = "cdr@mgdsw.info";
			$mail->Password = "+UI4cK~Jq2D@bFIB";  */
			
			
			
			if($from!='')
			 $mail->From = $from;
		     else
			 $mail->From = $setting[0]->from_email ;
		 
			if($fromname!='')
			 $mail->FromName = $fromname;
		     else
			 $mail->FromName = $setting[0]->from_name;
			
			$mail->AddAddress($to);
			$mail->IsHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $message;
			//$mail->addReplyTo(‘examle@examle.net’, ‘Information’);
			//$mail->addBCC(‘examle@examle.net’);
			//$mail->addAttachment(‘/home/kundan/Desktop/abc.doc’, ‘abc.doc’); // Optional name
			$mail->SMTPOptions= array(
			'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
			)
			);

			$mail->send();
			return true ;
		}catch (phpmailerException $e) {
				dd($e);
		} catch (Exception $e) {
				dd($e);
		}
		 return false ;
   }
// TOKEN 
	function getToken($length='')
	{
		if($length=='')
			$length =20;
		
		    $token = "";
		    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		    $codeAlphabet.= "0123456789";
		    $max = strlen($codeAlphabet); // edited

		    for ($i=0; $i < $length; $i++) {
		        $token .= $codeAlphabet[rand(0, $max-1)];
		    }

		    return $token;
	}
	
	
function sendNotification($sender_id,$reciever_id,$requested_id,$notification_id){
	$notification  =  new Notification();
	$notification->sender_id = $sender_id;
	$notification->reciever_id = $reciever_id;
	$notification->requested_id = $requested_id;
	$notification->notification_id = $notification_id;
	$notification->status = 0;
	$notification->save();
}

function sendEmailNotification($sender_id,$reciever_id,$template_name,$request_id){
	    $login_url = route('login');
		$email_template  = EmailTemplate::where('template_name',$template_name)->first();
		$userDetails = User::where('id',$sender_id)->first();
		$adminDetails  = User::where('id',$reciever_id)->first();
		$request_case = RequestCase::where('id',$request_id)->select('case_number')->first();
		$full_name = $userDetails->first_name.' '.$userDetails->last_name;
		if(strpos($email_template->subject, '[SENDER_NAME]')){
			$subject = str_replace('[SENDER_NAME]',$full_name,$email_template->subject);
		}else{
			$subject = $email_template->subject;
		}
		$message = $email_template->content;
		$message = str_replace('[SENDER_NAME]',$full_name,$message);
		$fromname = $userDetails->first_name.' '.$userDetails->last_name;
		$full_name_reciever = $adminDetails->first_name.' '.$adminDetails->last_name;
		$message = str_replace('[RECIEVER_NAME]',$full_name_reciever,$message);
		$message = str_replace('[CASE_NUMBER]',$request_case->case_number,$message);
		$message = str_replace('[LOGIN]',$login_url,$message);
		send_email($adminDetails->email,$subject,$message,$from='',$fromname);
	}
	
	
// GET EVENT DATA BY ROLE 
function eventbyRole($role_idArray){
	
	$data = EventLog::whereIn('role_id',$role_idArray)->where('event_name','!=','')->get();
	
	return $data;

}

// CREATE FAILED LOGIN EVENT 
function create_failed_attemp_log($username,$attempted_password){
	$logData = array();
	$logData['event_name'] 			= 'failed_login';
	$logData['username']   			= $username;
	$logData['attempted_password']  = $attempted_password;
	$logData['ipaddress']   		= get_client_ip();
	$auditData= AuditLog::create($logData);
}

// GET EVENT ID FROM EventLog TABLE 
function get_event_id($name){
	$event_id = EventLog::where('event_name',$name)->select('id')->first();
	return $event_id->id;
}

// GET THE IP ADDRESS 
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
  return $ipaddress;
}

// Show Site Title and LOGO 
function showSiteTitle($title){
	$setting = Setting::where('id',1)->first();
	
	if($setting && $title == 'title'){
		if($setting->site_title && $setting->site_title != ''){
			return $setting->site_title;
		}else{
			return trans('global.site_title');
		}
	}else if($setting && $title == 'logo'){
		if($setting->site_logo && $setting->site_logo != ''){

			return url('uploads/logo/'.$setting->site_logo);
		}else{
			return url('/img/logo.png');
		}
	}
}

function urlsafe_b64decode($string)
{
	$ciphering = "AES-128-CTR";
	$decryption_key = "GeeksforGeeks";
	$options = 0;
	$iv_length = openssl_cipher_iv_length($ciphering);
	$decryption_iv = '1234567891011121';
	return openssl_decrypt ($string, $ciphering,$decryption_key, $options, $decryption_iv);
}

/* Function For the image */ 
function timthumb($img,$w,$h){

		  $user_img =  url('plugin/timthumb/timthumb.php').'?src='.$img.'&w='.$w.'&h='.$h.'&zc=0&q=99';

		  return $user_img ;

}




function pr($data){

  echo "<pre>";
  print_r($data);
  echo "</pre>" ;die;
}


