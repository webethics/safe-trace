<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
//use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateUserPassword;
use App\Http\Requests\ResetPassword;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\CdrGroup;
use App\Models\SiteSetting;
use App\Models\EmailTemplate;
use Auth;
use Config;
use Response;
use Hash;
use App\Models\AuditLog;
use QrCode;
class CustomersController extends Controller
{
	public function customer_info($user_id){
		$data = User::where('id',$user_id)->select('business_name')->first();
		$business_name = $data->business_name;
		$site_data = SiteSetting::where('user_id',$user_id)->first();
		//print_r($site_data->toArray());die;
		return view('users.business_customers.index',compact('user_id','site_data'));	
	}
	
	public function customer_create(createCustomerRequest $request,$user_id){
		if($request->ajax()){
			$data = array();
			$result = User::where('id',$user_id)->select('business_name')->first();
			$business_name = $result->business_name;
		
		//	echo '<pre>';print_r();die;
			foreach($request->all() as $key=>$value){
				
				if($key == 'customer_name'){
					foreach($value as $k=>$v){
						$data[$k]['owner_name'] = $v;
					}
				}
				if($key == 'customer_email'){
					foreach($value as $k=>$v){
						$data[$k]['email'] = $v;
					}
				}
				if($key == 'customer_code'){
					foreach($value as $k=>$v){
						$data[$k]['text'] = $v;
					}
				}
				if($key == 'customer_text'){
					foreach($value as $k=>$v){
						$data[$k]['mobile_number'] = $v;
						$data[$k]['created_by'] = $user_id;
						$data[$k]['business_name'] =$business_name;
						$data[$k]['loyality_program'] = isset($request->loayality_program) ? 'yes':'no';
						$data[$k]['role_id'] = 3;
					}
				}
				
			}
		
			$error = false;
			foreach($data as $key=>$value){
				if($key == 1){
					$value['additional_guests'] =  $request->input('total_member')-1;
					$value['additional_guests_exists'] =  'yes';
				}
				if($key>1){
					$value['guest_with'] = $dataa->id;
				}
				if($key == 1){
					$dataa = User::create($value);
				}else{
					$data2 = User::create($value);
				}
				
				
				if(!$dataa){
					$error = true;
				} 
			}
			
			if(!$error){
				return Response::json(array(
					  'success'=>true,
					 ), 200);
				//return redirect('thankyou');	
				//return redirect('thankyou')->with('success','Please check your email for the activation link.'); 
			}
		}	
	}
	public function thankyou($user_id,$customer_name){
		$data = User::where('id',$user_id)->select('business_name')->first();
		$business_name = $data->business_name;
		$site_data = SiteSetting::where('user_id',$user_id)->first();
		//print_r($site_data->toArray());die;
		
		
		return view('users.business_customers.thankyou',compact('user_id','site_data','customer_name'));	
	}
	public function customerUpdate(UpdateCustomerRequest $request,$user_id){
		$data=array();
		$result =array();
		$requestData = User::where('id',$user_id);
		$stored_data = User::where('id',$user_id)->first()->toArray();
		 
		if($request->ajax()){
			$data =array();
			$data['owner_name']= $request->owner_name;
			//$data['business_name'] = $request->business_name;
			$data['email'] = $request->email;
			$data['mobile_number'] = $request->mobile_number;
			$data['text'] = $request->code;
			
			$requestData->update($data);
			
			//UPDATE PROFILE EVENT LOG END  
			$result['success'] = true;
			$result['name'] = $request->owner_name;
			$result['email'] = $request->email;
			$result['code'] = $request->code;
			$result['mobile_number'] = $request->mobile_number;
		   
		   return Response::json($result, 200);
		}
	}
	public function submit_contact(ContactFormRequest $request){
		
		//SEND EMAIL TO REGISTER USER.
		$contact_name = $request->name;
		$contact_email = $request->email;
		$contact_subject = $request->subject;
		$contact_message = $request->message;
		$logo = url('img/logo.png');
		$to = 'info@safe-trace.com';
		
		$uname = 'Admin';
		//EMAIL REGISTER EMAIL TEMPLATE 
		$result = EmailTemplate::where('template_name','contact_form')->first();
		$subject = $result->subject;
		$message_body = $result->content;
		
		$list = Array
		  ( 
			 '[NAME]' => $uname,
			 '[LOGO]' => $logo,
			 '[CONTACT_NAME]' => $contact_name,
			 '[CONTACT_EMAIL]' => $contact_email,
			 '[CONTACT_SUBJECT]' => $contact_subject,
			 '[CONTACT_MESSAGE]' => $contact_message,
		  );

		$find = array_keys($list);
		$replace = array_values($list);
		$message = str_ireplace($find, $replace, $message_body);
		
		//$mail = send_email($to, $subject, $message, $from, $fromname);
		
		$mail = send_email($to, $subject, $message);
		
		if($mail){
			$result['success'] = true;
			$result['message'] = "Your request has been submitted Successfully.";
		
		}else{
			$result['success'] = false;
			$result['message'] = "Some error occurred. Please try again Later.";
		
		}
		return Response::json($result, 200);
		
	}
	public function delete_customer($user_id){
		if($user_id){
			$main_user  = User::where('id',$user_id)->first();
			if($main_user->additional_guests > 0 && $main_user->additional_guests_exists == 'yes'){
				$guest_users = User::where('guest_with',$user_id)->get();
				foreach($guest_users as $guest){
					User::where('id',$guest->id)->delete();
				}
			} 
			if($main_user->guest_with != NULL){
				$data= array();
				$user_data = User::where('id',$main_user->guest_with);
				$get_main_users = User::where('id',$main_user->guest_with)->first();
				$additional_guests = $get_main_users->additional_guests - 1;
				$data['id'] = $main_user->guest_with;
				$data['additional_guests'] = $additional_guests;
				$user_data->update($data);
			}
			User::where('id',$user_id)->delete();
			$result =array('success' => true);	
			return Response::json($result, 200);
		}
	}
}

?>