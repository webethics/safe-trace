 <form action="{{ url('customer/advance-search') }}" method="POST" id="searchBusinessUsersForm" >
		@csrf
<div class="row">
	<div class="col-md-12 mb-4">
	<div class="card h-100">
		<div class="card-body">
			<div class="row">
				<div class="form-group col-lg-6">
					<input type="text" name="name" id="name" class="form-control" placeholder="{{trans('global.search_by_guest')}}">
				</div>
				<div class="form-group col-lg-6">
					<input type="text" name="email" id="email" class="form-control" placeholder="{{trans('global.search_by_email')}}">
				</div>
			</div>	
			<div class="row">
				
				

				<div class="col-lg-6">
				  <div class="form-group mb-4">
					  <div class="input-daterange input-group" id="datepicker">
						<input type="text" class="input-sm form-control" name="start_date" id="start_date"  placeholder="{{trans('global.start_date')}}" />
						
						<span class="input-group-addon">to</span>
						<input type="text" class="input-sm form-control" name="end_date" id="end_date" placeholder="{{trans('global.end_date')}}" />
						
					</div>

					</div>
				</div>
				
			</div>
			<div class="row"  style="display:none">
			  
			    <!-- If User is Super Admin --> 
				@if(current_user_role_id() == '1')
				<div class="form-group col-lg-6">
				<select  id="role_id"  class="form-control select2-single"  name="role_id"  data-width="100%">
							
							<option value=" ">{{trans('global.filter_by_role')}}</option>
							@foreach($roles as $key=>$role)
							@if($role->id!=1)
							<option value="{{$role->id}}">{{$role->title}}</option>
							@endif
							@endforeach
				</select>
				</div>
				@endif
			</div>	
			<div class="form-group">
				<button type="submit" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">{{trans('global.submit')}}</button>
				<button type="button" id="export_users" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">Export Customers</button>
				<div class="spinner-border text-primary search_spinloder" style="display:none"></div>
			</div>
			
		</div>
	</div>				
	</div>

	</div>	
</form>