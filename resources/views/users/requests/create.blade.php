@extends('layouts.admin')
@section('content')
@section('addEditRequestjs')
<script src="{{ asset('js/module/request.js')}}"></script>	
@stop
<div class="row">
                <div class="col-12">
                    <h1>Create Request</h1>
                    <div class="separator mb-5"></div>
                </div>
            </div>
           <div id="response"> </div>
            <div class="row">
                <div class="col-12 mb-4">			
                  <div class="card mb-4">
					<div class="col-lg-12 col-xl-8 mx-auto">
					   
                        <div class="card-body">
                           <form action="{{ route("user.requests.store") }}" method="POST" id="addRequest" >
							@csrf
								<div class="name_0_error errors alert alert-danger" style="display:none;text-align:center"></div>
								<div class="form-group form-row-parent row">
									<label class="col-lg-3 col-xl-2 col-form-label">{{ trans('global.request.fields.name') }}</label>
									<div class="col-lg-9 col-xl-10 addmore_field">
										<div class="d-flex control-group after-add-more" data-number="0" data-fieldnameForError="name">
											<input type="text" name="name[]" class="form-control" placeholder="Name">
											<button class="btn btn-light add-more" type="button">+</button>
										</div>
										<!--div class="name_0_error errors"></div-->
									</div>
								</div>
								<div class="form-group form-row-parent row">
									<label class="col-lg-3 col-xl-2 col-form-label">{{ trans('global.request.fields.company') }}</label>
									<div class="col-lg-9 col-xl-10 addmore_field" >
										<div class="d-flex control-group after-add-more" data-number="0" data-fieldnameForError="company">
											<input type="text" name="company[]" class="form-control" placeholder="{{ trans('global.request.fields.company') }}">
											<button class="btn btn-light add-more" type="button">+</button>
										</div>
										<!--div class="company_0_error errors"></div-->
									</div>								
								</div>	
							
								<div class="form-group form-row-parent row">
									<label class="col-lg-3 col-xl-2 col-form-label">{{ ucwords(trans('global.request.fields.url')) }}</label>
									<div class="col-lg-9 col-xl-10 addmore_field">
										<div class="d-flex control-group after-add-more" data-number="0" data-fieldnameForError="url">
											<input type="text" name="url[]" class="form-control" placeholder="{{ ucwords(trans('global.request.fields.url')) }}">
											<button class="btn btn-light add-more" type="button">+</button>
										</div>
										<!--div class="url_0_error errors"></div-->
									</div>	
								</div>	

								<div class="row form-row-parent">
								<label class="col-lg-3 col-xl-2 col-form-label">{{ ucwords(trans('global.request.fields.social_media')) }}</label>
								<div class="col-lg-9 col-xl-10 addmore_field">
                                <div class="form-row control-group after-add-more social_box_div" data-number="0">
									<div class="col-md-6 mb-3">
										<div class="form-group mb-0" data-fieldnameForError="social_type">
											<select  name="social_type[]" class="form-control select2-singl" data-width="100%">
												<option value="" selected>Choose...</option>
												<option value="facebook">Facebook</option>
												<option value="twitter">Twitter</option>
												<option value="likndin">likndin</option>
											</select>
										</div>
									<!--div class="social_type_0_error errors" ></div-->
                                    </div>
									<div class="col-md-6 mb-3">
                                    <div class="form-group mb-0 d-flex" data-fieldnameForError="social_name">
                                        <input type="text" class="form-control" name="social_name[]" id="inputCity">
										<button class="btn btn-light add-more" type="button" data-social="yes">+</button>
										 
                                    </div>
									<!--div class="social_name_0_error errors"></div-->
                                    </div>
									</div>
								</div>
							</div>									
	
								<div class="form-group row">
								<label class="col-lg-3 col-xl-2 col-form-label">{{ ucwords(trans('global.request.fields.other_info')) }}</label>
								<div class="col-lg-9 col-xl-10">
								<textarea class="form-control" name="other_info"  placeholder="{{ ucwords(trans('global.request.fields.other_info')) }}" rows="3"></textarea>
								</div>
								</div>		
							
								<div class="form-group row">
									<label class="col-lg-3 col-xl-2 col-form-label">{{ ucwords(trans('global.request.fields.priority')) }}</label>
									<div class="col-lg-9 col-xl-10">
									<div class="form-group">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" id="5_days" name="priority" 
										 value="normal">
										<label class="form-check-label" for="5_days">{{ ucwords(trans('global.request.fields.normal')) }}{{ ucwords(trans('global.request.fields.5_10_wokring_days')) }} </label>
									</div>
									

									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio"  name="priority" 
										 value="urgent">
										<label class="form-check-label" for="Urgent">{{ ucwords(trans('global.request.fields.urgent')) }}{{ ucwords(trans('global.request.fields.asap')) }}</label>
									</div>
									<div class="priority_error errors"> </div>
									</div>								
									</div>
								</div>	
								
								<div class="form-group row">
									<label class="col-lg-3 col-xl-2 col-form-label">{{ ucwords(trans('global.request.fields.data_archive')) }}</label>
									<div class="col-lg-9 col-xl-10">
									<div class="form-group">
									<div class="form-check form-check-inline">
										<input name ="data_archive[]" class="form-check-input" type="checkbox" id="Screenshot"
											value="screenshot">
										<label class="form-check-label" for="Screenshot">{{ ucwords(trans('global.request.fields.screenshot')) }}</label>
									</div>

									<div class="form-check form-check-inline">
										<input name ="data_archive[]" class="form-check-input" type="checkbox" id="Full"
											value="fullhtml">
										<label class="form-check-label" for="Full">{{ ucwords(trans('global.request.fields.fullpagehtml')) }}</label>
									</div>
									<div class="data_archive_error errors"> </div>
									</div>									
									</div>
								</div>	

															
							<div class="form-row mt-4">
							<div class="col-md-12 offset-lg-3 offset-xl-2">
							  
							<button type="submit" class="btn btn-primary default btn-lg mb-2 mb-sm-0 mr-2 col-12 col-sm-auto">Submit</button>
							<div class="spinner-border text-primary" style="display:none"></div>
							</div>
							
							</div>
                            </form>
                        </div>
                    </div>				
                    </div>				

                </div>
            </div>

@endsection