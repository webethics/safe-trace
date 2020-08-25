<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\AuditLog;
use Auth;
use Config;
class OtpController extends Controller
{
    
	// Show OTP form 
	public function ShowOtpForm()
    {   
         return view('auth.otp'); 
    }
	public function SendOtp(Request $request)
    {   
        $input = $request->all();
		$rules = array('otp' => 'required');
		$validator = Validator::make($request->input(), $rules);
		if ($validator->fails())
		{
			return redirect('send-otp')->withErrors($validator);
		}else
		{
			 $user = auth()->user();
			 $userData = User::where('id',$user->id);
			 $user_otp_exist = User::where('id',$user->id)->where('otp',$input['otp'])->get();

			// pr($userData);
			
			  if(count($user_otp_exist)>0){
				  
				 //Check OTP is correct or not
				 
				 // EVENT FOR LOGIN SUCCESS 
				 if($user_otp_exist[0]->role_id != 1){
					$logData = array();
					$user_id = user_id();
					$users = User::where('id',$user->id)->first();
					if($user_otp_exist[0]->role_id == 2){
						$logData['event_log_id'] 	= get_event_id('data_analyst_login');
					}
					if($user_otp_exist[0]->role_id == 3){
						$logData['event_log_id'] 	= get_event_id('customer_admin_login');
					}
					if($user_otp_exist[0]->role_id == 4){
						$logData['event_log_id'] 	= get_event_id('customer_login');
					}
					$logData['username']   	= $users->first_name.' '.$users->last_name;
					$logData['ipaddress']   = get_client_ip();
					$auditData= AuditLog::create($logData);
				}
				 
				 
				 $data = array();
				  $data['otp'] = NULL;
				  $userData->update($data);  
				 // get Redirect name on user role base 
				 return redirect(redirect_route_name());
			 }
			else{
				 $users = User::where('id',$user->id)->first();
				 create_failed_attemp_log($users->email,$input['otp']);
				 return redirect('send-otp')->with('error','You have Enter Wrong OTP.');
			} 
		}

    }

}
