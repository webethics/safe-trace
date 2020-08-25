 <form action="{{ url('requests/advance-search') }}" method="POST" id="searchForm" >
		@csrf
<div class="row">
	<div class="col-md-6 mb-4">
	<div class="card h-100">
		<div class="card-body">
			<div class="row">
				<div class="form-group col-lg-6">
					<input type="text" name="case_number" id="case_number" class="form-control" placeholder="{{trans('global.search_by_case_number')}}">
				</div>
				<div class="form-group col-lg-6">
					<input type="text" name="name" id="case_name" class="form-control" placeholder="{{trans('global.search_by_text')}}">
				</div>
			</div>	
			<div class="row">
			    @php 
				      $url_split = explodeTo('/',url()->current());
					  $url_name =end($url_split);
					 @endphp
				@if($url_name != 'reports')
				<div class="form-group col-lg-6">
				<select id="status" name="status" class="form-control select2-single" data-width="100%">
				 <option selected="" disabled>{{trans('global.filter_by_status')}}</option>
							<option value=" ">Select</option>
							<option value="1">{{trans('global.new')}}</option>
							<option value="2">{{trans('global.in_progress')}}</option>
							<option value="3">{{trans('global.completed')}}</option>
							<option value="4">{{trans('global.reopened')}}
							</option>
						   
						</select>

				</div>
				@endif
				<div class="form-group col-lg-6">
				<select  id="priority"  class="form-control select2-single"  name="priority" data-width="100%">
							<option value=" ">Select</option>
							<option selected="" disabled>{{trans('global.filter_by_priority')}}</option>
							<option value="normal">{{trans('global.request.fields.normal')}}</option>
							<option value="urgent">{{trans('global.request.fields.urgent')}}</option>
						</select>

				</div>
			</div>											
		</div>
	</div>				
	</div>
	<div class="col-md-6 mb-4 ">
	<div class="card h-100">
		<div class="card-body">

			<div class="row">
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
				<input type="hidden" class="form-control" name="url_name" id="url_name" value="{{$url_name}}">
			<div class="col-md-12">
			<button type="submit" class="btn btn-primary default  btn-lg mb-2 mb-lg-0 col-12 col-lg-auto">Search</button>
			<div class="spinner-border text-primary search_spinloder" style="display:none"></div>
			</div>									

			</div>
		</div>
	</div>				
	</div>
	</div>	
</form>