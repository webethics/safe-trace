@extends('layouts.admin')
@section('content')
@section('profilepageJsCss')
<script src="{{ asset('js/module/jquery.account.js')}}"></script>
@stop

<div class="row">
	<div class="col-12">
		<h1>{{trans('global.account_fields')}}</h1>
		<div class="separator mb-5"></div>
	</div>
</div>
<div class="row">
	<div class="col-12 mb-4">			
		<div class="card mb-4">
			<div class="row">
				<div class="col-md-3">
					<div class="card-header tabs-header">
						<ul class="nav nav-tabs vertical-tabs flex-column card-header-tabs " role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab"
									aria-controls="first" aria-selected="true">{{trans('global.basic')}}</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="second-tab" data-toggle="tab" href="#second" role="tab"
									aria-controls="second" aria-selected="false">{{trans('global.reset_password')}}</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="third-tab" data-toggle="tab" href="#third" role="tab"
									aria-controls="third" aria-selected="false">Send Email Notification</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="forth-tab" data-toggle="tab" href="#forth" role="tab"
									aria-controls="forth" aria-selected="false">Current Plan</a>
							</li>
						</ul>
					</div>				  
				</div>	
				<div class="col-md-9">						
					<div class="card-body">
						<div class="tab-content">
							<div id="msg" class="alert hide"></div>
							<div class="tab-pane fade show active" id="first" role="tabpanel"  aria-labelledby="first-tab">
								<form name="accountinfo" id="accountinfo" data-id="{{$user->id}}">		
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.owner_name')}}<em>*</em> </label>
										<div class="col-lg-9 col-xl-10">
										<div class="d-flex control-group">
										<input type="text" name="owner_name" id="owner_name" class="form-control" value="{{$user->owner_name}}">
										</div>
										<div class="owner_name_error errors"></div>
										</div>
										
									</div>
									@if(current_user_role_id()==2)
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.business_name')}}<em>*</em></label>
										<div class="col-lg-9 col-xl-10">
										<div class="d-flex control-group">
										<input type="text" name="business_name" id="business_name" class="form-control" value="{{$user->business_name}}">
										</div>
										<div class="business_name_error errors"></div>
										</div>
										
									</div>								
									@endif
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.email')}}<em>*</em></label>
										<div class="col-lg-9 col-xl-10 d-flex">
											<input type="email" name="email" id="email" class="form-control" value="{{$user->email}}" readonly>
										</div>
									</div>
									
									@if(current_user_role_id()==2)
										
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.phone_number')}}<em>*</em></label>
										
										
										<div class="col-lg-9 col-xl-10">
											<div class="d-flex control-group">
											<input type="text" name="mobile_number" id="mobile_number" class="form-control" value="{{$user->mobile_number}}">
											</div>
											<div class="mobile_number_error errors"></div>
										</div>
										
									</div>	

									
									
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.address')}}<em>*</em></label>
										<div class="col-lg-9 col-xl-10">
											<div class="d-flex control-group">
												<input type="text" name="address" value="{{$user->address}}" class="form-control" placeholder="{{trans('global.address')}}">
											</div>
											<div class="address_error errors"></div>
										</div>								
									</div>	
									
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.business_url')}}</label>
											<div class="col-lg-9 col-xl-10">
											<div class="d-flex control-group">
											
												<input name="business_url" id="business_url" class="form-control" type="text" value="{{$user->business_url}}" placeholder="{{trans('global.business_url')}}">
											</div>
											<div class="business_url_error errors"></div>
										</div>							
										
									</div>					
									
									
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.qr_code_label')}}</label>
										<div class="col-lg-9 col-xl-10">
										<div class="d-flex control-group">
										{!! QrCode::size(150)->generate($user->qr_code); !!}
										</div>				
										<a target = "_blank" href="{{ url('user/print')}}/{{$user->id}}">Print Your Code</a><br>
										<a target = "_blank" href="{{ url('customer-info')}}/{{$user->id}}">Open Url in Browser</a>	
										</div>	
									</div>	
									@endif
									
									<div class="form-row mt-4">
										<label class="col-lg-3 col-xl-2 col-form-label"></label>
										<div class="col-lg-9 col-xl-10">
											<!--input type="submit" id="update" value="Submit" class="btn btn-primary default btn-lg mb-1 mr-2"-->
											<button type="button" id="update" class="btn btn-primary default btn-lg mb-1 mr-2">{{trans('global.submit')}}</button>
										
										</div>
									</div>
									
								</form>
							</div>	


							<div class="tab-pane fade" id="second" role="tabpanel" aria-labelledby="second-tab">
								<form name="reset_pass" id="reset_pass" data-id="{{$user->id}}">
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.old_password')}}</label>
										<div class="col-lg-9 col-xl-10">
										<div class="d-flex control-group">
											<input type="password" name="old_password" id="old_password" class="form-control">
										</div>
										<div class="old_password_error errors"></div>
										</div>
										
									</div>
									
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.new_password')}}</label>
										<div class="col-lg-9 col-xl-10">
										<div class="d-flex control-group">
											<input type="password" name="password" id="password" class="form-control">
										</div>
										<div class="password_error errors"></div>
										</div>
										
									</div>								
									
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.confirm_password')}}</label>
										<div class="col-lg-9 col-xl-10">
										<div class="d-flex control-group">
											<input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
										</div>
										<div class="password_confirmation_error errors"></div>
										</div>
										
									</div>
									
									
									<div class="form-row mt-4">
										<label class="col-lg-3 col-xl-2 col-form-label"></label>
										<div class="col-lg-9 col-xl-10">
											<button type="button" id="reset" class="btn btn-primary default btn-lg mb-1 mr-2">{{trans('global.submit')}}</button>
										</div>
									</div>
								</form>
							</div>
							
							<div class="tab-pane fade" id="third" role="tabpanel" aria-labelledby="third-tab">
								<form name="email_notification" id="email_notification" data-id="{{$user->id}}">
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">QR Code Link</label>
										<div class="col-lg-9 col-xl-10">
										<div class="d-flex control-group">
										@if(current_user_role_id()==2)
											<input type="text" name="qr_code_link" disabled="disabled" id="qr_code_link" class="form-control" value="{{$user->qr_code_link}}">
										@endif
										@if(current_user_role_id()==1)
											<input type="text" name="qr_code_link" id="qr_code_link" class="form-control" value="{{$user->qr_code_link}}">
										@endif	
										</div>
										<div class="qr_code_link_error errors"></div>
										</div>
										
									</div>
									
									<div class="form-group row">
										<label class="col-lg-3 col-xl-2 col-form-label">Email</label>
										<div class="col-lg-9 col-xl-10">
										<div class="d-flex control-group">
											<input type="email" name="email" id="email" class="form-control">
										</div>
										<div class="email_error errors"></div>
										</div>
										
									</div>								
									
									
									<div class="form-row mt-4">
										<label class="col-lg-3 col-xl-2 col-form-label"></label>
										<div class="col-lg-9 col-xl-10">
											<button type="button" id="sendEmail" class="btn btn-primary default btn-lg mb-1 mr-2">{{trans('global.submit')}}</button>
										</div>
									</div>
								</form>
							</div>
							
							
											{{-- PLAN TAB --}}
							
								<div class="tab-pane fade global_settings" id="forth" role="tabpanel" aria-labelledby="forth-tab">
								
											 <div class="card h-100">
												<div class="card-body">
												<h5 class="mb-4">Your Current Plan</h5> 
												@if(isset($subs_data))
												@php 
												$plan_data = user_current_plan($subs_data->stripe_plan)
												@endphp
												
												<h3 class="font-weight-bold">${{$plan_data->cost}} <sub>/ month</sub><span class="time_period">{{$plan_data->plan_interval}} month subscription </span></h3></h3> 
											  
											 
											  @php 
												$to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $subs_data->created_at);
											  @endphp
											  @php
												$from = \Carbon\Carbon::now()	
											  @endphp
											  @php
													$diff_in_months = $to->diffInMonths($from)+1;
											  @endphp
										       @php
											   $end_date =  date('Y-m-d', strtotime($to->addMonths($plan_data->plan_interval)))
											  @endphp
											  
											   @if($subs_data->ends_at==NULL && $diff_in_months== $plan_data->plan_interval)
												<p class="subs_end"><b class="subs_end_txt">Subscription end at : {{Carbon\Carbon::parse($end_date)->format('l jS \\of F Y')}} </b></p>  
													{{--<div class="spinner-border text-primary loader_opencanel_subscription" style="display: none;"></div>
													<button type="button" id="cancel_subs"  class="btn btn-primary default btn-lg mb-1 mr-2 cancel_subscribeModal_Open" data-subscription_id="{{$subs_data->id}}">Cancel Subscription</button> 
								
												<div class="modal fade canelSubscrptionModal_{{$subs_data->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
												
												
												<p><b class="subscription_end_txt"></b><p>
													<button type="button" id="cancelled" style="display:none;background:red"  class="btn btn-primary default btn-lg mb-1 mr-2" data-subscription_id="{{$subs_data->id}}">Cancelled</button> --}}
												

												@else
													
												    @if(Carbon\Carbon::now()>$subs_data->ends_at && $subs_data->ends_at!=NULL)
													<p class="subs_end"><b class="subs_end_txt"> </b></p> 
													<p class="package_txt"> <b>Your package expired on:  {{Carbon\Carbon::parse($subs_data->ends_at)->format('l jS \\of F Y')}} </b> </p>
													<div class="spinner-border text-primary loader_renew_subscription" style="display: none;"></div>
													<button type="button" id="resume_subs" class="btn btn-primary default btn-lg mb-1 mr-2 resume_subscribeModal_Open" data-subscription_id="{{$subs_data->id}}">Renew</button>
												    
													@elseif(Carbon\Carbon::now()<=$subs_data->ends_at && $subs_data->ends_at!=NULL) 
													<p class="package_txt"><b>You can access package until:  {{Carbon\Carbon::parse($subs_data->ends_at)->format('l jS \\of F Y')}} </b> </p>
													<button type="button" id="resume_subs" style="background:red" class="btn btn-primary default btn-lg mb-1 mr-2" data-subscription_id="{{$subs_data->id}}">Cancelled</button>
													
													@else
														<p class="subs_end"><b>Subscription end at : {{Carbon\Carbon::parse($end_date)->format('l jS \\of F Y')}} </b></p> 
													
													@endif
													 
													 
													 <div class="modal fade resumeSubscrptionModal_{{$subs_data->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"></div>
													 
													 
												@endif
											 
												@else
													<h3 style="text-align:center;color:green">No Subscription Found.</h3>
											    @endif	
												
												
											 </div>
										  </div>
										
								
							   </div>
							
							
							
						</div>			
					</div>			
				</div>			
			</div>			
		</div>				

	</div>
</div>
@section('cancelsubscriptionJsAccountBlade')
<script>
/*==============================================
	OPEN CANCEL SUBSCRIPTION MODAL 
============================================*/
$(document).on('click', '.cancel_subscribeModal_Open' , function() {


 $('.loader_opencanel_subscription').css('display','inline-block');
	var subscription_id = $(this).data('subscription_id');
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	 $.ajax({
        type: "POST",
		dataType: 'json',
        url: '{{url('')}}/openCancelSubscriptionModal',
        data: {_token:csrf_token,subscription_id:subscription_id},
        success: function(data) {
			$('.loader_opencanel_subscription').css('display','none');
			if(data.success){
				$('.canelSubscrptionModal_'+subscription_id).html(data.data);
				$('.canelSubscrptionModal_'+subscription_id).modal('show');
				//$('.errors').html('');
			}else{
				notification('Error','Something went wrong.','top-right','error',3000);
			}	
        },
    });
})


/*==============================================
	CANCEL USER SUBSCRIPTION  
============================================*/
 $(document).on('click','.cancel_subscription', function(e) {

     e.preventDefault(); 
	$('.loader_cancel_subscription').css('display','inline-block');
	var csrf_token = $('input[name="_token"]').val();
	var subscription_id = $(this).data('subscription_id');
	
    $.ajax({
        type: "POST",
		dataType: 'json',
         url: base_url+'/cancel_subscription',
        data: {_token:csrf_token,subscription_id:subscription_id},
        success: function(data) {
			$('.error').html('');
			$('.loader_cancel_subscription').css('display','none');
			 if(data.success){
				notification('Success',data.msg,'top-right','success',3000);
				//$('#cancel_subs').removeClass('cancel_subscribeModal_Open').html('Resum');
				$('.cancel_subscribeModal_Open').hide();
				$('.subs_end').hide();
				$('#cancelled').show();
				$('.subscription_end_txt').html(data.subs_end_txt);
				//$('.subscribed_id_'+subscription_id).hide();
			}else{
				notification('Error',data.msg,'top-right','error',4000);
			}	 
			$('.canelSubscrptionModal_'+subscription_id).modal('hide');
        },
		error :function( data ) {}

    }); 
});	


/*==============================================
	OPEN RESUME SUBSCRIPTION MODAL 
============================================*/
$(document).on('click', '.resume_subscribeModal_Open' , function() {

    $('.loader_renew_subscription').css('display','inline-block');
	var subscription_id = $(this).data('subscription_id');
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	 $.ajax({
        type: "POST",
		dataType: 'json',
        url: '{{url('')}}/openResumeSubscriptionModal',
        data: {_token:csrf_token,subscription_id:subscription_id},
        success: function(data) {
			 $('.loader_renew_subscription').css('display','none');
			if(data.success){
				$('.resumeSubscrptionModal_'+subscription_id).html(data.data);
				$('.resumeSubscrptionModal_'+subscription_id).modal('show');
				//$('.errors').html('');
			}else{
				notification('Error','Something went wrong.','top-right','error',3000);
			}	
        },
    });
})


/*==============================================
	RENEW OR RESUME  USER SUBSCRIPTION  
============================================*/
 $(document).on('click','.resume_subscription', function(e) {

     e.preventDefault(); 
	$('.loader_resume_subscription').css('display','inline-block');
	var csrf_token = $('input[name="_token"]').val();
	var subscription_id = $(this).data('subscription_id');
	
    $.ajax({
        type: "POST",
		dataType: 'json',
         url: base_url+'/resume_subscription',
        data: {_token:csrf_token,subscription_id:subscription_id},
        success: function(data) {
			$('.error').html('');
			$('.loader_resume_subscription').css('display','none');
			 if(data.success){
				notification('Success',data.msg,'top-right','success',3000);
				//$('#resume_subs').removeClass('resume_subscribeModal_Open').html('Cancel');
				//$('.cancel_subscribeModal_Open').show();
				$('.resume_subscribeModal_Open').hide();
				$('.subs_end_date').html(data.subs_end_date);
				$('.subscription_end_txt').html('');
				//$('.subscribed_id_'+subscription_id).hide();
			}else{
				notification('Error',data.msg,'top-right','error',4000);
			}	 
			$('.resumeSubscrptionModal_'+subscription_id).modal('hide');
        },
		error :function( data ) {}

    }); 
});


</script>
@stop			

@endsection
