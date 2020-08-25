 <form action="{{ url('audit/advance-search') }}" method="POST" id="AuditsearchForm" >
		@csrf
<div class="row">
	<div class="col-md-6 mb-4">
	<div class="card h-100">
		<div class="card-body">
			<div class="row">
				<div class="form-group col-lg-12">
				<!-- IF CUSTOMER ADMIN -->
				@if(current_user_role_id()==$roleIdArr['CUSTOMER_ADMIN'])
				@php 
			    $role_id = array(4);
				$event = eventbyRole($role_id);
				@endphp 
				@endif
					<!-- IF DATA  ADMIN -->
				@if(current_user_role_id()==$roleIdArr['DATA_ADMIN'])
				@php 
			    $role_id = array(1,2,3,4);
				$event = eventbyRole($role_id);
				@endphp 
				@endif
				<select id="inputState" name="event_id" class="form-control select2-singl" data-width="100%">
				<option value=" ">Select Event</option>
				@foreach($event as $key =>$val)
				 <option value="{{$val->id}}">{{$val->event_title}}</option>
				@endforeach
				</select>
				
				</div>
				<div class="form-group col-lg-12">
				<input type="text" class="form-control" name="name" placeholder="Search by Name">
				</div>		
			</div>							
		</div>
	</div>				
	</div>
	<div class="col-md-6 mb-4 ">
	<div class="card h-100">
		<div class="card-body">
			<div class="row">
			<div class="col-12"><h5 class="mb-4">{{trans('global.search_by_time_stamp')}}</h5></div>
				<div class="col-lg-6">
				  <div class="form-group mb-4">
						<div class="input-group date">
							<input type="text" class="form-control"  id="start_date" name="start_date"
								placeholder="{{trans('global.start_date')}}">
							<span class="input-group-text input-group-append input-group-addon">
								<i class="simple-icon-calendar"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					   <div class="form-group mb-4">
						<div class="input-group date">
							<input type="text" class="form-control"  placeholder="{{trans('global.end_date')}}" name="end_date" id="end_date">
							
							<span class="input-group-text input-group-append input-group-addon">
								<i class="simple-icon-calendar"></i>
							</span>
						</div>
					</div>
				</div>
				
			<div class="col-md-12">
			<button type="submit" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">{{trans('global.search')}}</button>
			<div class="spinner-border text-primary search_spinloder" style="display:none"></div>
			</div>									

			</div>
		</div>
	</div>				
	</div>
	 </div>		
</form>