<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\AuditLog;
use App\Models\EventLog;
use App\Models\User;
use Auth;
use Config;
use Response;
class AuditsController extends Controller
{
	
	protected $per_page;
	
	public function __construct()
    {
	    
        $this->per_page = Config::get('constant.per_page');
    }
	
/*===========================================================
	 Listing Audit with pagination 
==============================================================*/	
	public function ajaxPagination(Request $request)
    {
		
		//$data_result = AuditLog::with('eventlogs')->where('id',1)->first()->toArray();
		//pr(array_filter($data_result));
		$roleIdArr = Config::get('constant.role_id');
		$audit_logs= $this->advance_search($request);
        if(!is_object($audit_logs)) return $audit_logs;
        if ($request->ajax()) {
            return view('users.audits.auditPagination', compact('audit_logs','roleIdArr'));
        }
        return view('users.audits.index',compact('audit_logs','roleIdArr')); 
    }
	
	/*==================================================
   //ADVNCE FILTER SEARCH 
==================================================*/
	public function advance_search($request)
	{
   
		$number_of_records =$this->per_page;
		$name = $request->name;
		$event_id = $request->event_id;
		$start_date = $request->start_date;
		$end_date = $request->end_date;
			//pr($request->all());
			//REQUEST SEARCH START FROM HERE
		
		 
		    $result  = \App\Models\AuditLog::with('eventlogs');
		
			$roleIdArr = Config::get('constant.role_id');
			if($name!='' || trim($event_id)!='' || $start_date!= '' || $end_date!=''){
				
				$search_name = '%' . $request->name . '%';
				if($start_date!= '' || $end_date!=''){
					if((($start_date!= '' && $end_date=='') || ($start_date== '' && $end_date!='')) || (strtotime($start_date) > strtotime($end_date))){	
						return  'date_error'; 
					}
				}
				
				$start_date_c = date('Y-m-d',strtotime($start_date));
				$end_date_c= date('Y-m-d',strtotime($end_date));
				
				
				 //CUSTOMER_USER  
				/*  if(current_user_role_id()== $roleIdArr['CUSTOMER_USER']){
					$result = $result->where('requested_user_id', '=', user_id());
				 } */
				 
				// check name 
				if(isset($name) && !empty($name)){
					
					$result->where('username','LIKE',$search_name);
				}
				
				// Check Priority 
			 	if(isset($event_id) && !empty($event_id)){
					$result->whereHas('eventlogs',function($query)use ($event_id){
					   return  $query->where('id',$event_id);   
					 });
				} 
				 // check date and time  
				if(!empty($start_date) &&  !empty($end_date)){
					$result->where(function($q) use ($start_date_c,$end_date_c) {
					$q->whereDate('created_at','>=' ,$start_date_c);
					$q->whereDate('created_at','<=', $end_date_c );
				  });
				} 
				//echo $result->toSql();
				//$requests = $result->paginate($number_of_records);
			   }
			
			    //IF URL REPORTS
				/*  if($request->url_name == 'reports'){
					 $result = $result->where('status', '=',3);
				 } */
				
				 // LISTING SHOW WITHOUT FILTER SELECTED 

				 // DATA_ANALYST
				/*  if(current_user_role_id()==$roleIdArr['DATA_ANALYST']){
					 $result = $result->where('assigned_user_id', '=', user_id());
				 } */
				 if(current_user_role_id()==$roleIdArr['DATA_ADMIN']){
					$role_ids =array(1,2,3,4);
				 }
				 //CUSTOMER_ADMIN SHOW ONLY HIS ADDED USER REQUEST AND HIS OWN
				
				 if(current_user_role_id()== $roleIdArr['CUSTOMER_ADMIN']){
					 $role_ids =array(4);					
				 } 
				
				$result->whereHas('eventlogs',function($query)use ($role_ids){
					   return  $query->whereIn('role_id',$role_ids);   
					 });
			  // echo $result->toSql();
				//die;
				$requests = $result->orderBy('created_at', 'desc')->paginate($number_of_records);
			
				return $requests ;
	}
	
	
	
   /*  public function ajaxPagination(Request $request)
    {
		
       $roleIdArr = Config::get('constant.role_id');
	   $number_of_records =$this->per_page;
	   if($roleIdArr['CUSTOMER_USER']== current_user_role_id()){
		  abort_unless(\Gate::denies(current_user_role_name()), 403);
	   }
	  
	   $role_id =4;
	   
	   $audit_logs  = \App\Models\Auditlog::with('eventlogs')->whereHas('eventlogs',function($query)use ($role_id){
		   return  $query->where('role_id',$role_id);   
	   })->orderBy('created_at','desc')->paginate($number_of_records);
	   
	  
	  // pr($audit_logs);
	 
		if ($request->ajax()) {
				return view('users.audits.auditPagination', compact('audit_logs'));
			} 
			 return view('users.audits.index',compact('audit_logs')); 
        
    }
	
 */

   /*===========================================================
    SHOW ASSIGN REQUEST MODAL TO ASSIGN REQUEST TO ANALYST
==============================================================*/	
	public function showEventDetail(Request $request)
    {
        $audit_id = $request->audit_id;
		$data_result = AuditLog::with('eventlogs')->where('id',$audit_id)->first()->toArray();
		if($data_result['changed_fields']){
			$data_result['changed_fields'] = json_decode($data_result['changed_fields'],true);
		}
		if($data_result['filename']){
			$data_result['filename'] = json_decode($data_result['filename'],true);
		}
		
		$view = view("modal.auditDetails",compact('data_result'))->render();
			$success = true;
        //abort_unless(\Gate::allows('request_edit'), 403);
		
		return Response::json(array(
		  'success'=>$success,
		  'data'=>$view
		 ), 200);
    }

    public function destroy(User $user)
    {
      

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, 204);
    }
}
