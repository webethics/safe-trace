<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use DB;
use App\Models\Plan;
use App\Models\User;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use Stripe\Stripe;
use Stripe\Subscription;
class SubscriptionController extends Controller
{
   protected function create(CreateUserRequest $request)
   {
	  $token = getToken();
	  $plan = Plan::where('stripe_plan',$request->plan_id)->get();
      if(count($plan)>0){	

		$dat =  User::create([
            'owner_name' => $request->owner_name,
            'business_name' => $request->business_name,
            'business_url' => $request->business_url,
            'mobile_number' => $request->mobile_number,
            'address' => $request->address,
            'email' => $request->email,
            'password' => Hash::make($token),
			'QR_code' =>  '',
			'role_id' => 2,
			'created_by' => 0,
			'status' => 1
        ]);
		
		$user = User::where('id',$dat->id)->first();
		$msg ='';
		try{
			$user->newSubscription('Safe Trace', $plan[0]->stripe_plan)
            ->create($request->stripeToken,[
			'email' => $request->email,
		  ]);
		   $msg = 'Please check your email for login details.';
			//SEND EMAIL TO REGISTER USER.
			$uname =$request->owner_name;
			$logo = url('/img/logo.png');
			$link= url('login');
			$to = $request->email;
			//EMAIL REGISTER EMAIL TEMPLATE 
			$result = EmailTemplate::where('id',20)->get();
			$subject = $result[0]->subject;
			$message_body = $result[0]->content;
			
			$list = Array
			  ( 
				 '[NAME]' => $uname,
				 '[USERNAME]' => $request->email,
				 '[PASSWORD]' => $token,
				 '[LINK]' => $link,
				 '[LOGO]' => $logo,
			  );

			$find = array_keys($list);
			$replace = array_values($list);
			$message = str_ireplace($find, $replace, $message_body);
			
			//$mail = send_email($to, $subject, $message, $from, $fromname);
			
			$mail = send_email($to, $subject, $message);  
			//Session::flash('message', "Please check your email for the activation link.");
			
			return redirect('login')->with('success',$msg); 

			
		}
		catch (\Stripe\Error\Base $e) {
		  // Code to do something with the $e exception object when an error occurs
		 
	      $msg=$e->getMessage();
		  return redirect('login')->with('success',$msg); 
		} catch (Exception $e) {
		   
	        $msg='Something went wrong';
			return redirect('login')->with('success',$msg); 
		}

		} 
		
    }
	
	//Run cron for expire the active subscription after the time that user subscribe
	
	public function checkSubscriptionPackage(){
		
		//GET ALL ACTIVE SUBSCRIPTION 
		
		 $subs_data =  DB::table('subscriptions')->whereNull('ends_at')->get();
		 
		// pr( $subs_data);
		//LOOP FOR ALL ACTIVE SUBSCRIPTION AND EXPIRED ONE DAY BEFORE THE EXPIRED DATE
		 foreach($subs_data as $key =>$value){
			 
			$plan = Plan::where('stripe_plan',$value->stripe_plan)->get();
			$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $value->created_at);
			$from = \Carbon\Carbon::now();
		    $diff_in_months = $to->diffInMonths($from)+1;

			$user = User::where('id',$value->user_id)->first();
			
			//$user->subscription('Safe Trace')->cancel();
			
			
			$subscriptionId = $user->subscription($value->name)->stripe_id;
            $apiKey = config('services.stripe.secret');
            Stripe::setApiKey($apiKey);

            $subscription = Subscription::retrieve($subscriptionId);
			
			//pr( $subscription);
			//
			//date('Y-m-d H:i:s',$subscription->current_period_end);
			//$end_date = date('Y-m-d H:i:s', strtotime('-1 day',$subscription->current_period_end));
			
			//GET THE ONE DAY BEFORE DATE THE EXPIRED
			 $end_date = date('Y-m-d', strtotime('-1 day',$subscription->current_period_end));
			//echo strtotime('2020-07-13 05:12:28');
			//$end_date ='2020-06-14';
			//$current_data = date('Y-m-d H:i:s');
			$current_data =date('Y-m-d');
				
			//$subscription_id =  $subscription_data[0]->stripe_id;
			
			//IF PLAN AND ENDTS_AT IS NULL 
			 if(count($plan)>0 && $value->ends_at==NULL){
					$plan_interval=	$plan[0]->plan_interval;
				
					//IF MONTH IS EQUAL TO EXPIRED MONTH
					if($diff_in_months == $plan_interval){
							try {
								//expire if current date is equal to the one day before the expired day.
								if($current_data==$end_date){
								  $user->subscription($value->name)->cancelNow();	 
								  echo 'Subscription Canceled SuccessFully';
								}
							} 
							catch (\Stripe\Error\Base $e) {
							  // Code to do something with the $e exception object when an error occurs
							  echo $e->getMessage();
							} catch (Exception $e) {
								echo 'Something went wrong';
							}
					}
			   }  	 
			}
	}
	
	
	
	
	
	
}
