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
use App\Models\Role;
use App\Models\User;
use Config;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Response;
use ZipArchive;
use File;
use Excel;
use App\Models\AuditLog;
class BusinessUsersController extends Controller
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
		
		$request->role_id = 3;
        $users= $this->advance_search($request);
		$roles = Role::all();
		$user_id = user_id();
		$business_name = User::where('id',$user_id)->select('business_name')->first();
		
        if(!is_object($users)) return $users;
        if ($request->ajax()) {
            return view('users.customers.customersPagination', compact('users','roles','business_name'));
        }
        return view('users.customers.business_index',compact('users','roles','business_name'));	
		
    }
	
	public function exportCustomers(Request $request){
		
		$request->role_id = 3;
		$user_id = user_id();
		$number_of_records =$this->per_page;
		$name = $request->name;
		$email = $request->email;
		$role_id = $request->role_id;
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		$result = User::where(`1`, '=', `1`);
		
		$result = User::where('created_by', '=', user_id());
		$roleIdArr = Config::get('constant.role_id');
		
		
		if($name!='' || $email!=''|| trim($role_id)!='' || $start_date!= '' || $end_date!=''){
			
			$user_name = '%' . $request->name . '%';
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
			
			//If Role is selected 
			if(isset($role_id) && !empty($role_id)){
				$result->where('role_id',$role_id);
			}
			$result->where('guest_with', '=', NULL);
			
			 // check date and time  
			if(!empty($start_date) &&  !empty($end_date)){
				$result->where(function($q) use ($start_date_c,$end_date_c) {
				$q->whereDate('created_at','>=' ,$start_date_c);
				$q->whereDate('created_at','<=', $end_date_c );
			  });
			} 
		}
		
	 
		if(current_user_role_id()== $roleIdArr['CUSTOMER_ADMIN']){
			$result->where('role_id', '=', $roleIdArr['CUSTOMER_USER']);
		} 
		 
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


/*==================================================
	   //ADVANCE FILTER SEARCH FOR USER 
==================================================*/
	public function advance_search($request)
	{
		$number_of_records =$this->per_page;
		$name = $request->name;
		$email = $request->email;
		$role_id = $request->role_id;
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		$result = User::where(`1`, '=', `1`);
		
		$result = User::where('created_by', '=', user_id());
		$roleIdArr = Config::get('constant.role_id');
		
		
		if($name!='' || $email!=''|| trim($role_id)!='' || $start_date!= '' || $end_date!=''){
			
			$user_name = '%' . $request->name . '%';
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
			
			//If Role is selected 
			if(isset($role_id) && !empty($role_id)){
				$result->where('role_id',$role_id);
			}
			$result->where('guest_with', '=', NULL);
			
			 // check date and time  
			if(!empty($start_date) &&  !empty($end_date)){
				$result->where(function($q) use ($start_date_c,$end_date_c) {
				$q->whereDate('created_at','>=' ,$start_date_c);
				$q->whereDate('created_at','<=', $end_date_c );
			  });
			} 
		}
		
	 
		if(current_user_role_id()== $roleIdArr['CUSTOMER_ADMIN']){
			$result->where('role_id', '=', $roleIdArr['CUSTOMER_USER']);
		} 
		 
		$users = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
		
		return $users ;
	}

	
}

