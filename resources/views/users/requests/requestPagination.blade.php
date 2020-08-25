@php $roleArr = Config::get('constant.role_id')  @endphp
	
<table class="table table-hover mb-0">
	<thead class="bg-primary">
		<tr>
		<th scope="col"> {{ trans('global.request.fields.case_number') }}</th>
		<th scope="col"> {{ trans('global.request.fields.priority')}}</th>
		<th scope="col">{{ trans('global.request.fields.requested_date')}}</th>
		
		<!-- IF USER NOT CUSTOMER_ADMIN AND CUSTOMER_USER THEN NOT SHOW BOTH FIELDS -->
		@if(current_user_role_id()!=$roleArr['CUSTOMER_ADMIN'] && current_user_role_id()!=$roleArr['CUSTOMER_USER'])
			
			<!-- IF USER NOT DATA_ANALYST THEN NOT SHOW ASSIGNED TO FIELD -->		
			@if(current_user_role_id()!=$roleArr['DATA_ANALYST'])
			<th scope="col">{{ trans('global.request.fields.assigned_to')}}</th>
			@endif 
		<th scope="col">{{ trans('global.request.fields.assigned_by')}}</th>
		@endif 
		
		<th scope="col">{{ trans('global.status')}}</th>	
		<th scope="col">{{ trans('global.actions')}}</th>										
		</tr>
	</thead>
	<tbody>
	
	 @if(is_object($requests) && !empty($requests) && $requests->count())
	  @foreach($requests as $key => $request)
			<tr data-request-id="{{ $request->id }}" >
					<td>
					
						<a href="javascript:void(0)"> {{ $request->case_number ?? '' }}</a>
					</td>
					
					
					
					<td>
						<a href="javascript:void(0)" id="row_{{ $request->id }}"> {{ ucwords($request->priority)  ?? '' }}</a>
					</td>
					
					<td> {{ $request->created_at  ?? '' }}</td>
			
			 <!-- Assigned to  --> 
			<!-- IF USER NOT CUSTOMER_ADMIN AND CUSTOMER_USER THEN NOT SHOW BOTH FIELDS -->
			@if(current_user_role_id()!=$roleArr['CUSTOMER_ADMIN'] && current_user_role_id()!=$roleArr['CUSTOMER_USER'])
			
					<!-- IF USER NOT DATA_ANALYST THEN NOT SHOW ASSIGNED TO FIELD -->		
					@if(current_user_role_id()!=$roleArr['DATA_ANALYST'])
						<td  id="assigned_to_{{ $request->id }}">
							 @if($request->assigned_user_id)
							 {{user_data_by_id($request->assigned_user_id)->first_name}} {{user_data_by_id($request->assigned_user_id)->last_name}}
								
							 @else - @endif
							
						 </td>
					@endif 
					
					<!-- Assigned by   -->  
					<td  id="assigned_by_{{ $request->id }}">
						 @if($request->assigned_user_id)
							 {{user_data_by_id($request->assignedBy)->first_name}} {{user_data_by_id($request->assignedBy)->last_name}}
								
						@else - @endif
					</td>	
				 
			@endif
				
           <!-- Status  -->
			@php
				$data = get_request_status_name($request->status);
				$status= $data['status'];
				$cls= $data['cls'];
			@endphp				 
			<td class="{{$cls}} text-light" id="status_{{ $request->id }}">
			{{$status}}</td>
		
			<!-- Action fields   -->
			<td>
				<!-- IF CUSTOMER_ADMIN AND CUSTOMER_USER NOT SHOW EDIT ICON -->
				@if((current_user_role_id()==$roleArr['CUSTOMER_ADMIN'] || current_user_role_id()==$roleArr['CUSTOMER_USER']) && $request->status==1)
				
					  <a href="javascript:void(0)" class="editRequest" data-request_id="{{ $request->id }}"><i class="simple-icon-note"></i></a>	
				
				@endif	

			   <!-- CHECK STATUS IF IN-PROGRESS/COMPLETE/REOPEN -->
				@if((current_user_role_id()==$roleArr['CUSTOMER_ADMIN'] || current_user_role_id()==$roleArr['CUSTOMER_USER']) && ($request->status==2 || $request->status==3 || $request->status==4))
				 
			    
				<a href="{{route('user.requests.show', $request->id)}}" class="" data-request_id="{{ $request->id }}"><i class="simple-icon-grid"></i></a>
				<!-- IF DATA ADMIN AND STATUS -->
				@elseif((current_user_role_id()==$roleArr['DATA_ADMIN'] || current_user_role_id()==$roleArr['DATA_ANALYST']) && ($request->status==2 || $request->status==3 || $request->status==4))

					<a href="{{url('requests/report')}}/{{$request->id}}" class="" data-request_id="{{ $request->id }}"><i class="simple-icon-grid"></i></a>

				@endif
				
				<!-- IF DATA ADMIN AND AND STATUS IS COMPLETE THEN SHOW REPOEN LINK  -->
				@if(current_user_role_id()==$roleArr['DATA_ADMIN'] && ($request->status==3))
				    <a href="javascript:void(0);" data-id="{{$request->id}}" data-confirm_type="complete" data-confirm_message ="Are you want to Change the status to 'Reopened' and Assign to analyst for report ?"  data-left_button_name ="Reopen" data-left_button_id ="status_reopen" data-left_button_cls="btn-primary" id="change_status_icon_{{$request->id}}"  class="open_confirmBox" ><i class="simple-icon-share"></i></a>

				@endif
				
				
				
				
					
				
				
				
				<!-- IF REQUEST IS NEW AND USER IS DATA_ADMIN THEN SHOW THIS ICON -->
				@if($request->status==1 && current_user_role_id()==$roleArr['DATA_ADMIN'])
				<a href="javascript:void(0)" class="requestAssignModal" id="assign_icon_{{ $request->id }}" data-request_id="{{ $request->id }}"><i class="simple-icon-share"></i></a>
					@endif
			 </td>
		</tr>
		
	 @endforeach
 @else
<tr><td colspan="7" class="error" style="text-align:center">No Data Found.</td></tr>
 @endif	
		
	</tbody>
</table>
 @if(is_object($requests) && !empty($requests) && $requests->count()) 
 {!! $requests->render() !!}
  @endif

