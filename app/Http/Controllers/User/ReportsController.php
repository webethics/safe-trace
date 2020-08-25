<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RequestCase;
use App\Models\User;
use Auth;
use Config;
use Hash;
class ReportsController extends Controller
{
	//Records per page 
	protected $per_page;
	
	public function __construct()
    {
	    
        $this->per_page = Config::get('constant.per_page');
    }
    public function ajaxPagination(Request $request)
    {
		
	
			$roleIdArr = Config::get('constant.role_id');
			//echo $roleIdArr['DATA_ANALYST'];die;
			$number_of_records =$this->per_page;
			$requests = RequestCase::where('status',3);
			$roleIdArr = Config::get('constant.role_id');
			
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

            $requests = $requests->orderBy('completed_at', 'desc')->paginate($number_of_records);

			if ($request->ajax()) {
				return view('users.requests.requestPagination', compact('requests'));
			} 
			return view('users.requests.index',compact('requests'));
		
		   
    }
	


	
}
