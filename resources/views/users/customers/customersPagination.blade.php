<table class="table table-hover mb-0">
	<thead class="bg-primary">
		<tr>
		<th scope="col">{{ trans('global.name') }}</th>
		@if(current_user_role_id()==1)
		<th scope="col">{{ trans('global.business_name') }}</th>
		@endif
		<th scope="col">{{ trans('global.email') }}</th>
		<th scope="col">{{ trans('global.registeration') }}</th>
		<th scope="col">{{ trans('global.phone_number') }}</th>
		<th scope="col">{{ trans('global.additional_guests') }}</th>
		<th scope="col">{{ trans('global.opt_in') }}</th>								
		<th scope="col">{{ trans('global.actions') }}</th>								
										
		</tr>
	</thead>
	<tbody>
	 @if(is_object($users) && !empty($users) && $users->count())
	  @foreach($users as $key => $user)
		<tr data-user-id="{{ $user->id }}"  class="user_row_{{$user->id}}"  >
			<td id="name_{{$user->id}}">{{ $user->owner_name ?? '' }}</td>
			@if(current_user_role_id()==1)
			<td id="name_{{$user->id}}">{{ $user->business_name ?? '' }}</td>
			@endif
			<td id="email_{{$user->id}}"> {{ $user->email  ?? '' }}</td>
			
			@php 
			$data = role_data_by_id($user->role_id);
			 $selected=''
			 @endphp
			 @if($user->status==1)
			@php	$selected = 'checked=checked'@endphp
		     @endif		
			
			<td id="created_at_{{$user->id}}"> {{ date('m/d/Y h:m A', strtotime($user->created_at))  ?? '' }}</td>
			<td id="mobile_number_{{$user->id}}">{{ $user->text  ?? '' }}-{{ $user->mobile_number  ?? '' }}</td>
			
			@if($user->additional_guests && $user->additional_guests > 0)
				<td id="created_at_{{$user->id}}"><a href="{{ url('guests')}}/{{$user->id}}"> {{ $user->additional_guests}}</a></td>
			@else
				<td id="created_at_{{$user->id}}"> {{ $user->additional_guests}}</td>
			@endif
			<td id="mobile_number_{{$user->id}}">{{ $user->loyality_program  ?? '' }}</td>
			
			<td>
				<a title="Edit Customer" href="javascript:void(0)" class="editCustomer" data-user_id="{{ $user->id }}"><i class="simple-icon-note"></i></a>
				
				<a title="Delete"  data-id="{{$user->id}}" data-confirm_type="complete" data-confirm_message ="Are you sure you want to delete the Customer?"  data-left_button_name ="Yes" data-left_button_id ="delete_customer" data-left_button_cls="btn-primary" class="open_confirmBox" href="javascript:void(0)" data-user_id="{{ $user->id }}"><i class="simple-icon-trash"></i></a>
			</td>
			
		</tr>
		
	 @endforeach
 @else
<tr><td colspan="7" class="error" style="text-align:center">No Data Found.</td></tr>
 @endif	
		
	</tbody>
</table> 
	<!------------ Pagination -------------->
		@if(is_object($users) && !empty($users) && $users->count()) 
		 {!! $users->render() !!}  
		 @endif	