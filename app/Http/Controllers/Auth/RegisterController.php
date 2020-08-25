<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Config;
use App\Models\Setting;
use App\Models\User;
use App\Models\EmailTemplate;
use Auth;
use App\Models\AuditLog;

use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use QrCode;
use Session;
use Illuminate\Auth\Events\Registered;
use Response;
use DB;
use App\Models\Plan;
use App\Http\Requests\CreateUserRequest;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
	

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }
	
	
	public function showRegistrationForm($plan_id)
	{
		//echo $plan; die;
		$plan_data = Plan::where('stripe_plan',$plan_id)->get();
		if(count($plan_data)>0){
		 $plan_data=$plan_data[0];
		return view('auth.register',compact('plan_data'));		
		}else{
			return redirect('/');
		}
		//print_r($plan_data); die;
		
	}
	
	
	
	public function checkemail(Request $req)
	{
		$email = $req->email;
		$emailcheck = DB::table('users')->where('email',$email)->count();
		if($emailcheck > 0)
		{
		 $result =array('success' => false,'msg'=>'The email has already been taken.');	
		}else{
			$result =array('success' => true,'msg'=>'');	
		}

		return Response::json($result, 200);
	}
	
	
	
	
	
	/* public function register(Request $request)
	{
		$this->validator($request->all())->validate();

		event(new Registered($user = $this->create($request->all())));

		// $this->guard()->login($user);

		return $this->registered($request, $user)
							?: redirect($this->redirectPath());
	 } */
	 

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
  /*   protected function validator(array $data)
    {
        return Validator::make($data, [
            'owner_name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_url' => ['required', 'string', 'max:255'],
			'mobile_number'   => ['required','numeric','regex:/[0-9]{9}/',], 
			'address'   => ['required',], 
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //'password' => ['required', 'string', 'min:8'],
        ]);
    } */

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    //protected function create(array $data)
    //{
		//$token = getToken();
		 /* $dat =  User::create([
            'owner_name' => $data['owner_name'],
            'business_name' => $data['business_name'],
            'business_url' => $data['business_url'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
			'QR_code' =>  '',
			'role_id' => 2,
			'created_by' => 0,
			'verify_token' =>  $token,
			
        ]);
		if($dat){
			//SEND EMAIL TO REGISTER USER.
			$uname = $data['owner_name'];
			$logo = url('/img/logo.png');
			$link= url('verify/account/'.$token);
			$to = $data['email'];
			//EMAIL REGISTER EMAIL TEMPLATE 
			$result = EmailTemplate::where('id',2)->get();
			$subject = $result[0]->subject;
			$message_body = $result[0]->content;
			
			$list = Array
			  ( 
				 '[NAME]' => $uname,
				 '[USERNAME]' => $data['email'],
				 '[PASSWORD]' => $data['password'],
				 '[LINK]' => $link,
				 '[LOGO]' => $logo,
			  );

			$find = array_keys($list);
			$replace = array_values($list);
			$message = str_ireplace($find, $replace, $message_body);
			
			//$mail = send_email($to, $subject, $message, $from, $fromname);
			
			$mail = send_email($to, $subject, $message);  
			Session::flash('message', "Please check your email for the activation link.");
			
			return $dat;
		} */
		//return redirect()->route('login')->with('error','Please check your email for the activation link.'); 
    //}
}
