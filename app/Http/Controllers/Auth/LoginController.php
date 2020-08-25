<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Config;
use App\Models\Setting;
use App\Models\User;
use App\Models\EmailTemplate;
use Auth;
use App\Models\AuditLog;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
	    
       //$this->middleware('guest')->except('logout');
    }
	
	public function login(Request $request)
    {   
		
        $input = $request->all();
		$rules = array('email' => 'required|email|exists:users,email',
				   'password' => 'required',
				   );

		$validator = Validator::make($request->input(), $rules);
		if ($validator->fails())
		{
			//EVENT FAILED
			create_failed_attemp_log($input['email'],$input['password']);
			return redirect('login')->withErrors($validator)->withInput($request->except('password'));
		}else
		{
			
			if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
			{ 
		        //IF STATUS IS NOT ACTIVE 
				if(Auth::check() && Auth::user()->verify_token !=NULL){
					//EVENT FAILED
					create_failed_attemp_log($input['email'],$input['password']);
					Auth::logout();
					return redirect('/login')->with('error', 'Your account is not verified.Please check your email and verify your account.');
				}else if(Auth::check() && Auth::user()->status == 0){ 
					//IF STATUS IS NOT ACTIVE 
					//EVENT FAILED
					create_failed_attemp_log($input['email'],$input['password']);
					Auth::logout();
					return redirect('/login')->with('error', 'Your account is deactivated.');
				}
				
				
			  $user = auth()->user();
			  $role_id =  $user->role_id;
			  $role_id = Config::get('constant.role_id');
			  
			
			  // DATA_ADMIN LOGIN 
			  if($role_id['DATA_ADMIN']== current_user_role_id()){
				  
					return redirect('users');	 
	 
			   }

			  /* USE/ANALYST/USER-ADMIN LOGIN SETTING ADMIN ENABLE DOUBLE AUTHENTICATION  */ 
			  $setting = Setting::where('user_id',1)->get();
			  //pr($setting);
			  // IF DOUBLE AUTHENTICATION IS ON 
			  if($setting[0]->double_authentication){
				  /* Send OTP to User in email or phone */
				    $otp  = getToken(7); 
				    $usertData = User::where('id',$user->id);
					$data =array();
					$data['otp'] =$otp; 
					$usertData->update($data);
					$to  = $user->email; 
					//EMAIL REGISTER EMAIL TEMPLATE 
					$result = EmailTemplate::where('template_name','one_time_otp')->get();
					$subject = $result[0]->subject;
					$message_body = $result[0]->content;
					$uname = $user->first_name .' '.$user->last_name;
					$logo = url('/img/logo.png');
					
					$list = Array
					  ( 
						 '[NAME]' => $uname,
						 '[OTP]' => $otp,
						 '[LOGO]' => $logo,
					  );

					$find = array_keys($list);
					$replace = array_values($list);
					$message = str_ireplace($find, $replace, $message_body);
	
					$mail = send_email($to, $subject, $message); 
				
				 /*   */
				 return redirect('send-otp')
				->with('message','Please check email or phone for OTP.');
				  
			  }else{			  
					// IF DOUBLE AUTHENTICATION IS OFF : ANALYST/ADMIN/USER/USER_ADMIN 
					 return redirect(redirect_route_name());
			  }
			}
			else{
				//EVENT FAILED
				
				return redirect()->route('login')
					->with('error','You have entered wrong details.');
			}
		}

    }
	
	
	
	
}
