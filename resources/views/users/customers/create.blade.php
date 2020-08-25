@extends('layouts.admin')
@section('content')
@section('profilepageJsCss')
<script src="{{ asset('js/module/jquery.account.js')}}"></script>
<script src="{{ asset('js/module/user.js')}}"></script>
@stop
<div class="row">
	<div class="col-12">
		<h1>{{trans('global.add_business_user')}}</h1>
		<div class="separator mb-5"></div>
	</div>
</div>

<div class="row">
	<div class="col-12 mb-4">							
	  <div class="card mb-4">
		
			<div class="card-body">
			<div id="msg" class="alert hide"></div>
				<form name="create_user" id="create_user" data-id="{{user_id()}}">	

					<div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.owner_name')}}</label>
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group">
								<input type="text" name="owner_name" class="form-control" placeholder="{{trans('global.owner_name')}}">
							</div>
							<div class="owner_name_error errors"></div>
						</div>	
					</div>
					
					
					<div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.business_name')}}</label>
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group">
								<input type="text" name="business_name" class="form-control" placeholder="{{trans('global.business_name')}}">
							</div>
							<div class="business_name_error errors"></div>
						</div>	
					</div>
					
					<!--div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.last_name')}}</label>
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group">
								<input type="text" name="last_name" class="form-control" placeholder="{{trans('global.last_name')}}">
							</div>
							<div class="last_name_error errors"></div>
						</div>								
					</div-->								
					
					
					
					<div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.email')}}</label>
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group">
								<input type="email" name="email" class="form-control" id="inputEmail4" placeholder="{{trans('global.email')}}">
							</div>
							<div class="email_error errors"></div>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.address')}}</label>
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group">
								<input type="text" name="address" class="form-control" placeholder="{{trans('global.address')}}">
							</div>
							<div class="address_error errors"></div>
						</div>								
					</div>	
					
					<!--div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.password')}}</label>
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group">
								<input type="password" name="password" id="password" class="form-control" placeholder="{{trans('global.password')}}">
							</div>
							<div class="password_error errors"></div>
						</div>
					</div>								
					
					<div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.confirm_password')}}</label>
						<div class="col-lg-9 col-xl-10">
						<div class="d-flex control-group">
							<input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{trans('global.confirm_password')}}">
						</div>
						<div class="password_confirmation_error errors"></div>
						</div>
						
					</div-->
					
					<div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.phone_number')}}</label>

						<!--div class="col-lg-2 col-xl-2 mb-3 mb-md-0">
							<div class="d-flex control-group">
							<input name="code_number" id="code_number" class="form-control" type="text" value="" placeholder="{{trans('global.phone_code')}}">	
							</div>
							
						</div-->	
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group">
							
								<input name="mobile_number" id="mobile_number" class="form-control" type="tel" value="" placeholder="{{trans('global.phone_number')}}">
							</div>
							<div class="mobile_number_error errors"></div>
						</div>							
						
					</div>	
					<div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.tax_number')}}</label>

						<!--div class="col-lg-2 col-xl-2 mb-3 mb-md-0">
							<div class="d-flex control-group">
							<input name="code_number" id="code_number" class="form-control" type="text" value="" placeholder="{{trans('global.phone_code')}}">	
							</div>
							
						</div-->	
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group">
							
								<input name="tax_number" id="tax_number" class="form-control" type="text" value="" placeholder="{{trans('global.tax_number')}}">
							</div>
							<div class="tax_number_error errors"></div>
						</div>							
						
					</div>	
					
					
					<!---
					@if(current_user_role_id()== $roleConstantArray['DATA_ADMIN'])
					<div class="form-group row">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.group')}}</label>
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group">
								<select name="group" class="form-control select2-single" data-width="100%" id="select_group">
									<option value="">{{trans('global.select_group')}}</option>
								    @foreach($groups as $key=>$group)
									<option value="{{$group->id}}">{{$group->group_name}}</option>
									@endforeach
								</select>
							</div>
							<div class="group_error errors"></div>
						</div>	
					</div>	
					
					<div class="form-group row data_provider">
						<label class="col-lg-3 col-xl-2 col-form-label">{{trans('global.role_type')}}</label>
						<div class="col-lg-9 col-xl-10">
							<div class="d-flex control-group" id="role_box">
								<select name="role_id" id="role_id" class="form-control select2-single" data-width="100%">
									<option value="">{{trans('global.select_role_type')}}</option>
								</select>
							</div>
							<div class="role_type_error errors"></div>
						</div>	
					</div>	
					
					@endif
					 -->
					<!--  Add hidden fiedls if user is data admin or customer admin -->
					@if(current_user_role_id()== $roleConstantArray['CUSTOMER_ADMIN'])
						<input name="group" type="hidden" id="group"  value="1">
						<input name="role_id" type="hidden" id="group"  value="5">
					@endif
					
												
				<div class="form-row mt-4">
				<div class="col-md-12 offset-lg-3 offset-xl-2">
				<button type="button" id="create" class="btn btn-primary default btn-lg mb-1 mr-2 col-12 col-sm-auto">{{trans('global.submit')}}</button>
				<button type="button" class="btn btn-dark btn-lg default  mb-1 col-12 col-sm-auto clear">{{trans('global.clear')}}</button>
				</div>
				</div>
				</form>
			</div>
				
		</div>				

	</div>
</div>
@endsection