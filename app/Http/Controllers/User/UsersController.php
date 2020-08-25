<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
//use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPassword;
use App\Http\Requests\sendEmailNotification;
use App\Http\Requests\ResetPassword;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\CdrGroup;
use App\Models\EmailTemplate;
use League\Csv\Writer;	
use Auth;
use Config;
use Response;
use Hash;
use App\Models\AuditLog;
use QrCode;
use App\Models\Plan;
use DB;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Subscription;
class UsersController extends Controller
{
	//Records per page 
	protected $per_page;
	private $qr_code_path;
	public function __construct()
    {
	    $this->qr_code_path = public_path('/uploads/qr_code/');
        $this->per_page = Config::get('constant.per_page');
    }
	
	public function landing_page()
    {
       
		$plan_data =Plan::all();
		return view('users.users.landing',compact('plan_data'));
		
		//return view('users.account.account');
    }
    public function ajaxPagination(Request $request)
    {
	
		//USER/ANALYST NOT ABLE TO ACCESS THIS 
		access_denied_user_analyst();
		
		$request->role_id = 2;
        $users= $this->advance_search($request,'');
		$roles = Role::all();
        if(!is_object($users)) return $users;
        if ($request->ajax()) {
            return view('users.users.usersPagination', compact('users','roles'));
        }
        return view('users.users.index',compact('users','roles'));	
	}
	// CREATE USER FORM 
    public function create()
    {
		// USER/ANALYST NOT ABLE TO ACCESS THIS 
		access_denied_user_analyst();
		$groups = CdrGroup::all();
		$roleConstantArray = Config::get('constant.role_id');
        return view('users.users.create',compact('groups','roleConstantArray'));
    }
	
	// ROLE DROPDOWN AJAX OPTION ON CREATE USER FORM 
    public function roleDropdown(Request $request)
    {
		if($request->ajax()){
			$request->group_id;
			$roles = Role::where('group_id',$request->group_id)->get();
			return view('users.users.role_dropdown',compact('roles'));
		}
	
    }
	
    // ADD USER 
   public function store(CreateUserRequest $request, $user_id)
   {
		
		 // USER/ANALYST NOT ABLE TO ACCESS THIS 
		 access_denied_user_analyst();
		// IF AJAX
		if($request->ajax()){
			$data =array();
			$token = getToken();
			$data['business_name']	= $request->business_name;
			$data['owner_name'] = $request->owner_name; 
			$data['email'] = $request->email;
			//$hashed = Hash::make($request->password);
			//$hashed = Hash::make($this->password_generate(7));
			$hashed = Hash::make('Teamwebethics3!');
			$data['password'] = $hashed;
			$data['mobile_number'] = $request->mobile_number;			
			/* $data['tax_number'] = $request->tax_number;	 */		
			$data['address'] = $request->address;			
			$data['business_url'] = $request->business_url;	
			
			// IF USER IS SUPER ADMIN 
			$roleConstantArray = Config::get('constant.role_id');
			if(current_user_role_id()==$roleConstantArray['DATA_ADMIN']){	
				$data['role_id'] = 2;	
			}
		
			$data['created_by'] = user_id();	
			$data['verify_token'] = $token;	
			
			$data['QR_code'] =  '';//QrCode::size(200)->generate($request->business_name);
			
			
			$dat = User::create($data);
			
			//SEND EMAIL TO REGISTER USER.
			$uname = $request->owner_name;
			$logo = url('img/logo.png');
			$link= url('verify/account/'.$token);
			$to = $request->email;
			//EMAIL REGISTER EMAIL TEMPLATE 
			$result = EmailTemplate::where('id',2)->get();
			$subject = $result[0]->subject;
      		$message_body = $result[0]->content;
      		
      		$list = Array
              ( 
                 '[NAME]' => $uname,
				 '[USERNAME]' => $request->email,
				 '[PASSWORD]' => $request->password,
                 '[LINK]' => $link,
                 '[LOGO]' => $logo,
              );

      		$find = array_keys($list);
      		$replace = array_values($list);
      		$message = str_ireplace($find, $replace, $message_body);
			
			//$mail = send_email($to, $subject, $message, $from, $fromname);
			
			$mail = send_email($to, $subject, $message);
			//return redirect('password/reset')->with('success','Please check your email for password reset.');
			
			return Response::json(array(
			  'success'=>true,
			 ), 200);
			 
		
		}
    }
	/*--------------- Customer Listing ------------------------ */
	public function ajaxCustomerPagination(Request $request)
    {
	
		//USER/ANALYST NOT ABLE TO ACCESS THIS 
		//access_denied_user_analyst();
		$request->role_id = 3;
		$all_business = User::where('role_id',2)->select('id','business_name')->get();
		$users= $this->advance_customer_search($request,'');
		$roles = Role::all();
		$dropdown_display = true;
		$showsearchform = true;
        if(!is_object($users)) return $users;
        if ($request->ajax()) {
            return view('users.customers.customersPagination', compact('users','roles','all_business','dropdown_display'));
        }
		$user_id = '';
		
        return view('users.customers.index',compact('users','roles','user_id','all_business','dropdown_display'));	
	}
	
	public function ajaxGuestPagination(Request $request,$user_id)
    {
	
		//USER/ANALYST NOT ABLE TO ACCESS THIS 
		//access_denied_user_analyst();
		$request->role_id = 3;
		//$request->guest_with = $user_id;
		$main_customer = User::where('id',$user_id)->select('owner_name')->first();
		$all_business = User::where('role_id',2)->select('id','business_name')->get();
		$users= $this->advance_guest_search($request,$user_id);
		$roles = Role::all();
		$dropdown_display = true;
        $showsearchform = false;
        if(!is_object($users)) return $users;
        if ($request->ajax()) {
            return view('users.customers.guestsPagination', compact('users','roles','all_business','dropdown_display','main_customer'));
        }
		$user_id = '';
		
        return view('users.customers.guests',compact('users','roles','user_id','all_business','dropdown_display','main_customer'));	
	}
	
	public function ajaxCustomerListingPagination(Request $request,$user_id)
    {
		$user_id = $user_id;
		//USER/ANALYST NOT ABLE TO ACCESS THIS 
		//access_denied_user_analyst();
		$request->role_id = 3;
		$all_business = User::where('role_id',2)->select('id','business_name')->get();
		$business_name = User::where('id',$user_id)->select('business_name')->first();
        $users= $this->advance_customer_search($request,$user_id);
		$roles = Role::all();
		$dropdown_display = false;
        if(!is_object($users)) return $users;
        if ($request->ajax()) {
            return view('users.customers.customersPagination', compact('users','roles','all_business','dropdown_display','business_name'));
        }
        return view('users.customers.index',compact('users','roles','user_id','all_business','dropdown_display','business_name'));	
	}
	
	
/*==================================================
	 SHOW USER PROFILE 
==================================================*/ 
	public function account()
    {
        $user = user_data();
		$user_id = $user->id;
		$user->qr_code = '<a href="'.url('customer-info').'/'.$user_id.'">Open Link</a>';
		$user->qr_code_link = url('customer-info').'/'.$user_id;
		$subs_data=  DB::table('subscriptions')->where('user_id',$user_id)->orderBy('id','DESC')->first();
		return view('users.account.account', compact('user','subs_data'));
		//return view('users.account.account');
    }
	
/*==================================================
	 SHOW USER PROFILE 
==================================================*/ 
	public function edit($user_id)
    {
		
        $user = User::where('id',$user_id)->get();
		$roles = Role::all();
		if(count($user)>0){
			$user =$user[0];
			$user->qr_code = '<a href="'.url('customer-info').'/'.$user_id.'">Open Link</a>';
			$view = view("modal.userEdit",compact('user','roles'))->render();
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
	
	public function print_code($user_id){
		$user = User::where('id',$user_id)->first();
		if($user){
			$image_path = 'qrcode'.$user_id.'.svg';
			$url_to_scan = '<a href="'.url('customer-info').'/'.$user_id.'"></a>';
			QrCode::size(200)->generate($url_to_scan, $this->qr_code_path.$image_path);
			return view('users.customers.print',compact('image_path','user'));	
		}
	}
	
	public function customer_edit($user_id)
    {
		
        $user = User::where('id',$user_id)->get();
		$roles = Role::all();
		if(count($user)>0){
			$user =$user[0];
			$view = view("modal.customerEdit",compact('user','roles'))->render();
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
	
/*==================================================
	  UPDATE USER PROFILE 
==================================================*/  
	public function profileUpdate(UpdateUserRequest $request,$user_id)
    {
		$data=array();
		 $result =array();
		//pr($request->all());
		 $requestData = User::where('id',$user_id);
		 $stored_data = User::where('id',$user_id)->first()->toArray();
		 
		if($request->ajax()){
			$data =array();
			$data['business_name']= $request->business_name;
			$data['owner_name']= $request->owner_name;
			$data['mobile_number'] = $request->mobile_number;
			$data['business_url'] = $request->business_url;
			$data['address'] = $request->address;
			
			$requestData->update($data);
			
			//UPDATE PROFILE EVENT LOG END  
		   $result['success'] = true;
		   $result['name'] = $request->owner_name;
		   $result['mobile_number'] = $request->mobile_number;
		   $result['business_name']= $request->business_name;
		   $result['business_url']= $request->business_url;
		   
		   return Response::json($result, 200);
		}
		
    }

/*==================================================
	  CHANGE PASSWORD 
==================================================*/ 
	public function passwordUpdate(UpdateUserPassword $request,$user_id)
    {
		// IF AJAX
		if($request->ajax()){
			$data=array();
			$userData = user_data();
			$userUpdate = User::where('id',$user_id);
			$newPassword=$request->password; //NEW PASSWORD
			$hashed = $userData->password;  //DB PASSWORD
	   
			if(Hash::check($request->old_password, $hashed)){
				$hashed = Hash::make($newPassword);
				
				$data['password'] = $hashed;			
				$userUpdate->update($data);
				$result =array(
				'success' => true
				);	
			}else{
				$result =array(
				'success' => false,
				'errors' => array('old_password'=>'Password does not match.')
				);	
			}
			return Response::json($result, 200);
		}
    }	
	

/*==================================================
	  sendEmailNotification
==================================================*/ 
	public function sendEmailNotification(sendEmailNotification $request,$user_id)
    {
		// IF AJAX
		if($request->ajax()){
			
			/* $user = user_data();
			$user_id = $user->id; 
			$user->qr_code = '<a href="'.url('customer-info').'/'.$user_id.'">Open Link</a>';
			$user->qr_code_link = url('customer-info').'/'.$user_id;
			return view('users.account.account', compact('user'));*/
			
			
			$uname = $request->owner_name;
			$logo = url('img/logo.png');
			$link= url('customer-info').'/'.$user_id;
			$to = $request->email;
			//EMAIL REGISTER EMAIL TEMPLATE 
			$result = EmailTemplate::where('template_name','email_notification')->first();
			$subject = $result->subject;
      		$message_body = $result->content;
      		
      		$list = Array
              ( 
                 '[LINK]' => $link,
                 '[LOGO]' => $logo,
              );

      		$find = array_keys($list);
      		$replace = array_values($list);
      		$message = str_ireplace($find, $replace, $message_body);
			
			//$mail = send_email($to, $subject, $message, $from, $fromname);
			
			$mail = send_email($to, $subject, $message);
			
			if($mail){
				
				$result =array(
					'success' => true
				);	
			}else{
				$result =array(
					'success' => false,
					'errors' => array('old_password'=>'Unable to send email.')
				);	
			}
			return Response::json($result, 200);
		}
    }	
	
/*==================================================
	   //ADVANCE FILTER SEARCH FOR USER 
==================================================*/
	public function advance_search($request,$user_id)
	{
			
		    // USER/ANALYST NOT ABLE TO ACCESS THIS 
		//	access_denied_user_analyst();
			$number_of_records =$this->per_page;
			$name = $request->name;
			$business_name = $request->business_name;
			$email = $request->email;
			$role_id = $request->role_id;
			
			//pr($request->all());
			//USER SEARCH START FROM HERE
			$result = User::where(`1`, '=', `1`);
		//	$result = User::where('id', '!=', user_id());
			$roleIdArr = Config::get('constant.role_id');
			
			
			if($business_name!='' || $name!='' || $email!=''|| trim($role_id)!=''){
				
				$user_name = '%' . $request->name . '%';
				$business_name = '%' . $request->business_name . '%';
				$email_q = '%' . $request->email .'%';
				
				
				
				// check email 
				if(isset($email) && !empty($email)){
					$result->where('email','LIKE',$email_q);
				} 
				// check name 
				if(isset($name) && !empty($name)){
					
					$result->where('owner_name','LIKE',$user_name);
				}
				if(isset($business_name) && !empty($business_name)){
					
					$result->where('business_name','LIKE',$business_name);
				}
				
				//If Role is selected 
				if(isset($role_id) && !empty($role_id)){
					$result->where('role_id',$role_id);
				}
			
				
				//	echo  $result->toSql();
				
			  // USER SEARCH END HERE   
			 }
			
			if($user_id){
				$result->where('created_by', '=', $user_id);
			}
			
				
			 $users = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
			
			return $users ;
	}
	public function advance_customer_search($request,$user_id)
	{
			
		// USER/ANALYST NOT ABLE TO ACCESS THIS 
		//access_denied_user_analyst();
		$number_of_records =$this->per_page;
		$name = $request->name;
		$business_name = $request->business_name;
		$business_id = $request->business_id;
		$email = $request->email;
		$role_id = $request->role_id;
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		
		//pr($request->all());
		//USER SEARCH START FROM HERE
		$result = User::where(`1`, '=', `1`);
		
	
		$roleIdArr = Config::get('constant.role_id');
		
		
		if($business_name!='' || $name!='' || $email!=''|| $business_id!=''|| trim($role_id)!=''  || $start_date!= '' || $end_date!=''){
			
			$user_name = '%' . $request->name . '%';
			$business_name = '%' . $request->business_name . '%';
			$email_q = '%' . $request->email .'%';
			if($start_date!= '' || $end_date!=''){
				if((($start_date!= '' && $end_date=='') || ($start_date== '' && $end_date!='')) || (strtotime($start_date) >= strtotime($end_date))){	
					return  'date_error'; 
				}
			}
			
			$start_date_c = date('Y-m-d',strtotime($start_date));
			$end_date_c= date('Y-m-d',strtotime($end_date));
			
			// check email 
			if(isset($email) && !empty($email)){
				$result->where('email','LIKE',$email_q);
			} 
			// check name 
			if(isset($name) && !empty($name)){
				
				$result->where('owner_name','LIKE',$user_name);
			}
			if(isset($business_name) && !empty($business_name)){
				
				$result->where('business_name','LIKE',$business_name);
			}
			
			//If Role is selected 
			if(isset($role_id) && !empty($role_id)){
				$result->where('role_id',$role_id);
			}
			if(isset($business_id) && !empty($business_id)){
				$result->where('created_by',$business_id);
			}
			
			if(!empty($start_date) &&  !empty($end_date)){
				$result->where(function($q) use ($start_date_c,$end_date_c) {
				$q->whereDate('created_at','>=' ,$start_date_c);
				$q->whereDate('created_at','<=', $end_date_c );
			  });
			}
		
			
			//	echo  $result->toSql();
			
		  // USER SEARCH END HERE   
		 }
		
		if($user_id){
			$result->where('created_by', '=', $user_id);
		}
		
		 $result->where('guest_with', '=', NULL);
		  //	echo  $result->toSql();
		//echo '<pre>';print_r($result->toArray());die;	
		 $users = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
		
		return $users ;
	}
	
	public function advance_guest_search($request,$user_id)
	{
			
		    // USER/ANALYST NOT ABLE TO ACCESS THIS 
		//	access_denied_user_analyst();
			$number_of_records =$this->per_page;
			$name = $request->name;
			$business_name = $request->business_name;
			$business_id = $request->business_id;
			$email = $request->email;
			$role_id = $request->role_id;
			
			//pr($request->all());
			//USER SEARCH START FROM HERE
			$result = User::where(`1`, '=', `1`);
			
		//	$result = User::where('id', '!=', user_id());
			$roleIdArr = Config::get('constant.role_id');
			
			
			if($business_name!='' || $name!='' || $email!=''|| $business_id!=''|| trim($role_id)!=''){
				
				$user_name = '%' . $request->name . '%';
				$business_name = '%' . $request->business_name . '%';
				$email_q = '%' . $request->email .'%';
				
				
				// check email 
				if(isset($email) && !empty($email)){
					$result->where('email','LIKE',$email_q);
				} 
				// check name 
				if(isset($name) && !empty($name)){
					
					$result->where('owner_name','LIKE',$user_name);
				}
				if(isset($business_name) && !empty($business_name)){
					
					$result->where('business_name','LIKE',$business_name);
				}
				
				//If Role is selected 
				if(isset($role_id) && !empty($role_id)){
					$result->where('role_id',$role_id);
				}
				if(isset($business_id) && !empty($business_id)){
					$result->where('created_by',$business_id);
				}
			
				
			  // USER SEARCH END HERE   
			 }
			
			if($user_id){
				$result->where('guest_with', '=', $user_id);
			}
			
			$users = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
			
			return $users ;
	}
	
	// ENABLE/DISABLE 
	public function enableDisableUser(Request $request)
	{
		if($request->ajax()){
			$user = User::where('id',$request->user_id);

			$data =array();
			$data['status'] =  $request->status;
			$user->update($data);
			
			// Show message on the basis of status 
			if($request->status==1)
			 $enable =true ;
			if($request->status==0)
			 $enable =false ;
		  
		   $result =array('success' => $enable);	
		   return Response::json($result, 200);
		}
		
	}

	//VERIFY ACCOUNT  
	public function verifyAccount($token)
    {
		
		$result = User::where('verify_token', '=' ,$token)->get();
		$notwork =false; 
		if(count($result)>0){
			if($result[0]->created_by == 0){
				$userUpdate = User::where('email',$result[0]->email);
				$data['verify_token'] =NULL;			
				$data['status'] =1;		
				$data['created_by'] = 1;
				$userUpdate->update($data);
				return redirect('login')->with('success','Your account is verified.');	;
			}else{
				$url_post = url('password/reset_new_user_password');
				$notwork =true;  
				return view('auth.passwords.reset',compact('token','notwork','url_post'));	
			}
			/* $userUpdate = User::where('email',$result[0]->email);
			$data['verify_token'] =NULL;			
			$data['status'] =1;			
			$userUpdate->update($data); */
			
		}else{
			 return redirect('login')->with('error','Your Link is not correct to reset password.');	;
		}
		
		
        	
    }
	public function exportUsers(Request $request){
		
		$request->role_id = 2;
		$number_of_records =$this->per_page;
		$name = $request->name;
		$business_name = trim($request->business_name);
		$email = $request->email;
		$role_id = $request->role_id;
		
		$result = User::where(`1`, '=', `1`);
		
		$roleIdArr = Config::get('constant.role_id');
		
		if($business_name != '' && $name !='' || $email !=''|| trim($role_id) != ''){
			
			$user_name = '%' . $request->name . '%';
			$business_name_1 = '%'.$request->business_name.'%';
			$email_q = '%' . $request->email .'%';
			
			
			
			// check email 
			if(isset($email) && !empty($email)){
				$result->where('email','LIKE',$email_q);
			} 
			// check name 
			if(isset($name) && !empty($name)){
				
				$result->where('owner_name','LIKE',$user_name);
			}
			if(isset($business_name_1) && !empty($business_name_1)){
				
				$result->where('business_name','LIKE',$business_name_1);
			}
			
			//If Role is selected 
			if(isset($role_id) && !empty($role_id)){
				$result->where('role_id',$role_id);
			}
		}
		
		$users = $result->orderBy('created_at', 'desc')->get();
		if($users && count($users) > 0){
			$records = [];
			foreach ($users as $key => $user) {
				$records[$key]['sl_no'] = ++$key;
				$records[$key]['name'] = $user->owner_name;
				$records[$key]['business_name'] = $user->business_name;
				$records[$key]['email'] = $user->email;
				$records[$key]['phone'] = $user->mobile_number;
				$records[$key]['business_url'] = $user->business_url;
				$records[$key]['qr_code_link'] = url('customer-info').'/'.$user->id;
				$records[$key]['address'] =  $user->address;
				$records[$key]['registraion'] =  date('m/d/Y h:m A', strtotime($user->created_at));
			}
			$header = ['S.No.', 'Contact Name','Business Name', 'Email','Phone', 'Business URL', 'QR Code Link', 'Address', 'Registration Date/Time'];
		

			//load the CSV document from a string
			$csv = Writer::createFromString('');

			//insert the header
			$csv->insertOne($header);

			//insert all the records
			$csv->insertAll($records);
			@header("Last-Modified: " . @gmdate("D, d M Y H:i:s",$_GET['timestamp']) . " GMT");
			@header("Content-type: text/x-csv");
			// If the file is NOT requested via AJAX, force-download
			if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
				header("Content-Disposition: attachment; filename=search_results.csv");
			}
			//
			//Generate csv
			//
			echo $csv;
			exit();
		}else{
			$result =array('success' => false);	
		    return Response::json($result, 200);
		}
		
		
	}
	public function exportListingCustomers(Request $request,$user_id){
		
		$request->role_id = 3;
		
		$number_of_records =$this->per_page;
		$name = $request->name;
		$business_name = $request->business_name;
		$business_id = $request->business_id;
		$email = $request->email;
		$role_id = $request->role_id;
		$start_date = $request->start_date;
		$end_date = $request->end_date;
			
		$result = User::where(`1`, '=', `1`);
			
		
		$roleIdArr = Config::get('constant.role_id');
			
		
		if($business_name!='' || $name!='' || $email!=''|| $business_id!=''|| trim($role_id)!=''  || $start_date!= '' || $end_date!=''){
			
			$user_name = '%' . $request->name . '%';
			$business_name = '%' . $request->business_name . '%';
			$email_q = '%' . $request->email .'%';
			if($start_date!= '' || $end_date!=''){
				if((($start_date!= '' && $end_date=='') || ($start_date== '' && $end_date!='')) || (strtotime($start_date) >= strtotime($end_date))){	
					return  'date_error'; 
				}
			}
			
			$start_date_c = date('Y-m-d',strtotime($start_date));
			$end_date_c= date('Y-m-d',strtotime($end_date));
			
			// check email 
			if(isset($email) && !empty($email)){
				$result->where('email','LIKE',$email_q);
			} 
			// check name 
			if(isset($name) && !empty($name)){
				
				$result->where('owner_name','LIKE',$user_name);
			}
			if(isset($business_name) && !empty($business_name)){
				
				$result->where('business_name','LIKE',$business_name);
			}
			
			//If Role is selected 
			if(isset($role_id) && !empty($role_id)){
				$result->where('role_id',$role_id);
			}
			if(isset($business_id) && !empty($business_id)){
				$result->where('created_by',$business_id);
			}
			
			if(!empty($start_date) &&  !empty($end_date)){
				$result->where(function($q) use ($start_date_c,$end_date_c) {
				$q->whereDate('created_at','>=' ,$start_date_c);
				$q->whereDate('created_at','<=', $end_date_c );
			  });
			}
		}
			
		if($user_id){
			$result->where('created_by', '=', $user_id);
		}
			
		$result->where('guest_with', '=', NULL);
		$users = $result->orderBy('created_at', 'desc')->get();
		if($users && count($users) > 0){
			$records = [];
			foreach ($users as $key => $user) {
				
				$records[$key]['sl_no'] = ++$key;
				$records[$key]['customer_name'] = $user->owner_name;
				$records[$key]['customer_email'] = $user->email;
				$records[$key]['customer_text'] = $user->text.'-'.$user->mobile_number;
				$records[$key]['guest_registration'] = date('m/d/Y h:m A', strtotime($user->created_at));
				$records[$key]['opt_in'] = $user->loyality_program;
				$records[$key]['guest_with'] = "";
				if($user->additional_guests > 0 && $user->additional_guests_exists == 'yes'){
					$guest_users = User::where('guest_with',$user->id)->get();
					foreach($guest_users as $guestkey=>$guest){
						$records[$key.'_'.$guestkey]['sl_no'] = '';
						$records[$key.'_'.$guestkey]['guest_name'] = $guest->owner_name;
						$records[$key.'_'.$guestkey]['guest_email'] = $guest->email;
						$records[$key.'_'.$guestkey]['guest_text'] = $guest->text.'-'.$guest->mobile_number;
						$records[$key.'_'.$guestkey]['guest_registration'] = date('m/d/Y h:m A', strtotime($guest->created_at));
						$records[$key.'_'.$guestkey]['opt_in'] ="";
						$records[$key.'_'.$guestkey]['guest_with'] = $user->owner_name;
					}
				}   
			}
			$header = ['S.No.', 'Customer Name', 'Email', 'Phone', 'Registration Date/Time',  'Opt In','Guest With'];
			

			//load the CSV document from a string
			$csv = Writer::createFromString('');

			//insert the header
			$csv->insertOne($header);

			//insert all the records
			$csv->insertAll($records);
			@header("Last-Modified: " . @gmdate("D, d M Y H:i:s",$_GET['timestamp']) . " GMT");
			@header("Content-type: text/x-csv");
			// If the file is NOT requested via AJAX, force-download
			if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
				header("Content-Disposition: attachment; filename=search_results.csv");
			}
			//
			//Generate csv
			//
			echo $csv;
			exit();
		}else{
			$result =array('success' => false);	
		    return Response::json($result, 200);
		}	
		
	}
	
    // LOGOUT 
	public function logout()
    {
      
		$logData = array();
		$user_id = user_id();
		$users = User::where('id',$user_id)->first();
		
		 Auth::logout();
		 return redirect('login');
		
		
    }
	
	public function delete_user($user_id){
		if($user_id){
			User::where('id',$user_id)->delete();
			$result =array('success' => true);	
			return Response::json($result, 200);
		}
	}
	
	function password_generate($chars) 
	{
	  $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
	  return substr(str_shuffle($data), 0, $chars);
	}

   /*  public function destroy(User $user)
    {
        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    } */
	
	
/*===========================================================
    OPEN CANCEL SUBSCRIPTION MODAL 
==============================================================*/	
	public function openCancelSubscriptionModal(Request $request)
    {
        $subscription_id   = $request->subscription_id;
		$user_id = user_id();
		$subscription_data=  DB::table('subscriptions')->where('id',$request->subscription_id)->where('user_id',$user_id)->get();
		
		$subscription_data=$subscription_data[0];
		$view = view("users.account.cancel_subscription_modal",compact('subscription_data'))->render();
		$success = true;
		
		 return Response::json(array(
		  'success'=>$success,
		  'data'=>$view
		 ), 200); 
   }
   
/* ======================================================
* CANCEL SUBSCRIPTION 
=============================================================*/
    public function cancelSubscription(Request $request){

		$subscription_id= $request->subscription_id;
		$user_id = user_id();
		$subscription_data =  DB::table('subscriptions')->where('id',$request->subscription_id)->where('user_id',$user_id)->whereNull('ends_at')->get();
	    $result['subs_end_txt']='';
	   if(count($subscription_data)>0){
	    $user = User::where('id',$user_id)->first();
		$subscription_id =  $subscription_data[0]->stripe_id;
		
		try {
		  $user->subscription($subscription_data[0]->name)->cancel();
		  $subs_data =  DB::table('subscriptions')->where('id',$request->subscription_id)->where('user_id',$user_id)->get();
		  $end_date = Carbon::parse($subs_data[0]->ends_at)->format('l jS \\of F Y');
		  $result['subs_end_txt']='You can access package :' . $end_date;
		  $result['success']=true;
	      $result['msg']='Subscription Canceled SuccessFully';
		
		} 
		catch (\Stripe\Error\Base $e) {
		  // Code to do something with the $e exception object when an error occurs
		 
		  $result['success']=false;
	      $result['msg']=$e->getMessage();
		} catch (Exception $e) {
		    $result['success']=false;
	        $result['msg']='Something went wrong';
		}
	   }else{
		   $result['success']=false;
	       $result['msg']='Something went wrong';
	   }
     	return Response::json($result,200);		
		
    }
	
	
/*===========================================================
    OPEN RESUME SUBSCRIPTION MODAL 
==============================================================*/	
	public function openResumeSubscriptionModal(Request $request)
    {
        $subscription_id   = $request->subscription_id;
		$user_id = user_id();
		$subscription_data=  DB::table('subscriptions')->where('id',$request->subscription_id)->where('user_id',$user_id)->get();
		$plan_data=  Plan::where('stripe_plan',$subscription_data[0]->stripe_plan)->first();
		
		$subscription_data=$subscription_data[0];
		$view = view("users.account.resume_subscription_modal",compact('subscription_data','plan_data'))->render();
		$success = true;
		
		 return Response::json(array(
		  'success'=>$success,
		  'data'=>$view
		 ), 200); 
   }
   
/* ======================================================
* RENEW  SUBSCRIPTION 
=============================================================*/
    public function resumeSubscription(Request $request){

		$subscription_id= $request->subscription_id;
		$user_id = user_id();
		$subscription_data =  DB::table('subscriptions')->where('id',$subscription_id)->where('user_id',$user_id)->whereNotNull('ends_at')->get();
		 
	    $result['subs_end_txt']='';
	    if(count($subscription_data)>0){
	    $user = User::where('id',$user_id)->first();
		$subscriptionId =  $subscription_data[0]->stripe_id;
		$stripe_plan =  $subscription_data[0]->stripe_plan;

        $apiKey = config('services.stripe.secret');
        Stripe::setApiKey($apiKey);
        $subscription = Subscription::retrieve($subscriptionId);
		
		//$current_date = strtotime(date('Y-m-d H:i:s'));
		try {
		  //$subs_data =  DB::table('subscriptions')->where('id',$request->subscription_id)->where('user_id',$user_id)->get();
		  //$end_date = Carbon::parse($subs_data[0]->ends_at)->format('l jS \\of F Y');
		  //$result['subs_end_txt']='You can access package :' . $end_date;
		 // $subscription = $user->subscription($subscription_data[0]->name);
		/*  if ($subscription->cancelled() && $subscription->onGracePeriod()) { 
			//if it was cancelled by user in grace period
			$subscription->resume();
			$result['success']=true;
	        $result['msg']='Subscription Resume SuccessFully';
		 }else { */ 
		//if cancelled by payment failure or smth else...
		$current_date = strtotime(date('Y-m-d H:i:s'));
		//renew if current date is greater then expiry date.
		//echo date('Y-m-d H:i:s',$subscription->current_period_end);
		//echo "<br>". date('Y-m-d H:i:s',$current_date);
		$result['subs_end_date']='';
		if($current_date > $subscription->current_period_end){

			 if($user->subscription($subscription_data[0]->name)) {
					$user->newSubscription($subscription_data[0]->name,
							$user->subscription($subscription_data[0]->name)->stripe_plan)
						->create();
						
				   $subs_data =  DB::table('subscriptions')->where('user_id',$user_id)->whereNull('ends_at')->orderBy('id','DESC')->first();
				   if($subs_data){
					   $plan = Plan::where('stripe_plan',$stripe_plan)->first();					  
					   $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $subs_data->created_at);
					   $end_date = $to->addMonths($plan->plan_interval);
					   $end_date = Carbon::parse($end_date)->format('l jS \\of F Y');
				    
					$result['subs_end_date']='Subscription end at :' . $end_date;
					$result['success']=true;
					$result['msg']='Subscription Renewed SuccessFully';
				   }
			   } 
		    } else{
			   
			 $result['success']=false;
			 $result['msg']='You can not renew before expire the subscription';  
		   }
		 }			 
		catch (\Stripe\Error\Base $e) {
		  // Code to do something with the $e exception object when an error occurs
		  $result['success']=false;
	      $result['msg']=$e->getMessage();
		} catch (Exception $e) {
		    $result['success']=false;
	        $result['msg']='Something went wrong';
		}
	   }else{
		   $result['success']=false;
	       $result['msg']='Something went wrong';
	   }
     	return Response::json($result,200);		
		
    }
	
	
}
