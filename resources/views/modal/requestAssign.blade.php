<div class="modal-dialog" role="document">
	<div class="modal-content">
	<div class="modal-header py-1">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<div class="modal-body">
	<form action="{{ url('request/assign') }}" method="POST" id="assignRequest" >
	 @csrf
	
		<!--- If user is super admin then show role dropdown -->
		@php $roleArr = Config::get('constant.role_id')  @endphp
		@if(current_user_role_id()==$roleArr['DATA_ADMIN'])
		<div class="form-group form-row-parent">
		<label class="col-form-label">{{ trans('global.data_analyst') }}</label>
		<div class="d-flex control-group">
			<select class="form-control select2-single" name="user_id" data-width="100%">
			@foreach($analyst as $key=>$user)
			<option value="{{$user->id}}" >{{$user->first_name}} {{$user->last_name}}</option>
			@endforeach
			</select>						
		</div>	
		</div>	
		@endif
								
		<div class="form-row mt-4">
		<div class="col-md-12 offset-lg-3 offset-xl-2">
		<input id ="request_id" name ="request_id" class="form-check-input" type="hidden" value="{{$request_id}}">
		<button type="submit" class="btn btn-primary default btn-lg mb-2 mb-sm-0 mr-2 col-12 col-sm-auto">{{ trans('global.assign') }}</button>
		<div class="spinner-border text-primary request_loader" style="display:none"></div>
		</div>
		</div>
		</form>

				</div>
			</div>
		</div>