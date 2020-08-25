 <form action="{{ url('customer/advance-search') }}" data-user_id = "{{$user_id}}" method="POST" id="searchCustomerForm" >
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
			
			<div class="row" >
			  
			    <!-- If User is Super Admin --> 
				@if($dropdown_display)
				<div class="form-group col-lg-6">
					<select  id="business_id"  class="form-control select2-single"  name="business_id"  data-width="100%">
						<option value=" ">Select Business Name</option>
						@foreach($all_business as $key=>$business)
							<option value="{{$business->id}}">{{$business->business_name}}</option>
						@endforeach
					</select>
				</div>
				@endif
				
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
			<div class="form-group">
				<button type="submit" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">{{trans('global.submit')}}</button>
				<button type="button" id="export_users_customers" data-id="{{$user_id}}" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">Export Customers</button>
				<div class="spinner-border text-primary search_spinloder" style="display:none"></div>
			</div>
			
		</div>
	</div>				
	</div>

	</div>	
</form>