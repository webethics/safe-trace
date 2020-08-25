<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header py-1">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<div class="modal-body">
			<form action="{{ url('requests/update/') }}/{{ $request->id }}" method="POST" id="updateRequest" >
				@csrf
				        <!-------------  NAME  FIELD ---------->
						<div class="form-group form-row-parent">
							<label class="col-form-label">{{ trans('global.request.fields.name') }}</label>
							@php
							 $i=0;
							@endphp
							<div class="addmore_field">
							@foreach(explodeTo('|',$request->name) as $key => $name)
								 @if($i==0)
									<div class="d-flex control-group after-add-more" data-number="0" data-fieldnameForError="name">
										<input type="text" name="name[]" class="form-control" placeholder="Name" value="{{$name}}">
										<button class="btn btn-light add-more" type="button">+</button>
									</div>
									<div class="name_{{$i}}_error errors"></div>	
								
								    @else
								
									<div class="d-flex control-group after-add-more mt-3" data-number="0" data-fieldnameForError="name">
										<input type="text" name="name[]" class="form-control" placeholder="Name" value="{{$name}}">
										<button class="btn btn-light remove" type="button">-</button>
									</div>
									<div class="name_{{$i}}_error errors"></div>	
									
								@endif
								@php
								  $i++;
								@endphp
							@endforeach
							</div>
						</div>
						 <!-------------   COMPANY  FIELD ---------->
						<div class="form-group form-row-parent">
							<label class="col-form-label">{{ trans('global.request.fields.company') }}</label>
							@php
							 $c=0;
							@endphp
							<div class="addmore_field" >
							@foreach(explodeTo('|',$request->company) as $key => $company)
								 @if($c==0)
							
								<div class="d-flex control-group after-add-more" data-number="0" data-fieldnameForError="company">
									<input type="text" name="company[]" class="form-control" placeholder="{{ trans('global.request.fields.company') }}" value="{{$company}}">
									<button class="btn btn-light add-more" type="button">+</button>
								</div>
								<div class="company_{{$c}}_error errors"></div>
							
							@else	
							
								<div class="d-flex control-group after-add-more mt-3" data-number="0" data-fieldnameForError="company">
									<input type="text" name="company[]" class="form-control" placeholder="{{ trans('global.request.fields.company') }}" value="{{$company}}">
									<button class="btn btn-light remove" type="button">-</button>
								</div>
								<div class="company_{{$c}}_error errors"></div>
							
							@endif
								@php
								  $c++;
								@endphp
							@endforeach	
							</div>
						</div>	
					   <!-------------   URL  FIELD ---------->
						<div class="form-group form-row-parent">
							<label class="col-form-label">{{ ucwords(trans('global.request.fields.url')) }}</label>
							
							@php
							 $u=0;
							@endphp
							<div class="addmore_field" >
							@foreach(explodeTo('|',$request->url) as $key => $url)
								 @if($u==0)
								<div class="d-flex control-group after-add-more" data-number="0" data-fieldnameForError="url">
									<input type="text" name="url[]" class="form-control" placeholder="{{ ucwords(trans('global.request.fields.url')) }}" value="{{$url}}">
									<button class="btn btn-light add-more" type="button">+</button>
								</div>
								<div class="url_{{$u}}_error errors"></div>
							@else	
								<div class="d-flex control-group after-add-more" data-number="0" data-fieldnameForError="url">
									<input type="text" name="url[]" class="form-control" placeholder="{{ ucwords(trans('global.request.fields.url')) }}" value="{{$url}}">
									<button class="btn btn-light remove" type="button">-</button>
								</div>
								<div class="url_{{$u}}_error errors"></div>
							@endif
									@php
									  $u++;
									@endphp
							@endforeach	
							</div>	
						</div>	
						 <!-------------   SOCIAL  FIELD ---------->
						<div class=" form-row-parent">
						<label class="col-form-label">{{ ucwords(trans('global.request.fields.social_media')) }}</label>
						 @php
						 $social_media= json_decode($request->social_media);
						  $s=0;
						 @endphp
						
						<div class="addmore_field">
						@foreach((array)$social_media as $key => $socialMedia)
						<!-- SHOW SELECTED VALUE IN SOCIAL TYPE DROPDOWN -->
								@php 	
								$facebook ='';
								$twitter ='';
								$linkdin ='';
								@endphp
								@if($key =='facebook')
									@php 									
									$facebook = 'selected=selected';
									@endphp
								@endif
								
								@if($key =='twitter') 	 
									@php 									
									$twitter = 'selected=selected';
									@endphp
								@endif
								@if($key =='linkdin') 	 
									@php 									
									$linkdin = 'selected=selected';
									@endphp
								@endif
							
							 @if($s==0) 
							<div class=" control-group after-add-more social_box_div" data-number="0">
								<div class="form-group mb-3" data-fieldnameForError="social_type">
									<select  name="social_type[]" class="form-control select2-singl" data-width="100%">
										<option value="">Choose...</option>
										<option value="facebook" {{$facebook}} >Facebook</option>
										<option value="twitter" {{$twitter}}>Twitter</option>
										<option value="linkdin" {{$linkdin}}>likndin</option>
									</select>
								</div>
								<div class="social_type_{{$s}}_error errors" ></div>
								<div class="form-group mb-3 d-flex" data-fieldnameForError="social_name">
									<input type="text" class="form-control" name="social_name[]" id="inputCity" value="{{$socialMedia}}">
									<button class="btn btn-light add-more" type="button" data-social="yes">+</button>
									 
								</div>
							<div class="social_name_{{$s}}_error errors"></div>
							</div>
							@else
								
							<div class=" control-group after-add-more social_box_div" data-number="0">
								<div class="form-group mb-3" data-fieldnameForError="social_type">
									<select  name="social_type[]" class="form-control select2-singl" data-width="100%">
										<option value="">Choose...</option>
										<option value="facebook" {{$facebook}} >Facebook</option>
										<option value="twitter" {{$twitter}}>Twitter</option>
										<option value="linkdin" {{$linkdin}}>likndin</option>
									</select>
								</div>
								<div class="social_type_{{$s}}_error errors" ></div>
								<div class="form-group mb-3 d-flex" data-fieldnameForError="social_name">
									<input type="text" class="form-control" name="social_name[]" id="inputCity" value="{{$socialMedia}}">
									<button class="btn btn-light remove" type="button" data-social="yes">-</button>
									 
								</div>
							<div class="social_name_{{$s}}_error errors"></div>
							</div>
								
							@endif
							@php
								$s++;
							@endphp							
							
							@endforeach
						</div>
					</div>									
						 <!-------------   OTHER INFO   FIELD ---------->
						<div class="form-group">
						<label class="col-form-label">{{ ucwords(trans('global.request.fields.other_info')) }}</label>
						<textarea class="form-control" name="other_info"  placeholder="{{ ucwords(trans('global.request.fields.other_info')) }}" rows="3">{{$request->other_info}}</textarea>
						</div>		
						 <!-------------   PRIORITY  FIELD ---------->
						<div class="form-group">
							<label class="col-form-label">{{ ucwords(trans('global.request.fields.priority')) }}</label>
							
							 @php $normal='';$urgent=''; @endphp
							 @if($request->priority=='normal')
								@php
								 $normal = 'checked=checked';
								@endphp
							 @elseif($request->priority=='urgent')
								@php
						    	 $urgent = 'checked=checked';
							   @endphp
							 @endif	
							<div class="form-group">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" id="5_days" name="priority" 
								 value="normal" {{$normal}}>
								<label class="form-check-label" for="5_days">{{ ucwords(trans('global.request.fields.normal')) }}{{ ucwords(trans('global.request.fields.5_10_wokring_days')) }} </label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio"  name="priority" 
								 value="urgent" {{$urgent}}>
								<label class="form-check-label" for="Urgent">{{ ucwords(trans('global.request.fields.urgent')) }}{{ ucwords(trans('global.request.fields.asap')) }}</label>
							</div>
							<div class="priority_error errors"> </div>
							</div>								

						</div>	
						<!-------------   DATA ARCHIVE  FIELD ---------->
						<div class="form-group">
							<label class="col-form-label">{{ ucwords(trans('global.request.fields.data_archive')) }}</label>
		
							<div class="form-group">
							  @php $screenshot='';$fullhtml=''; @endphp
							  @foreach (explodeTo('|',$request->data_archive) as $key => $dataArchive)
							    @if($dataArchive=='screenshot')
									@php $screenshot='checked=checked'; @endphp
								@endif
								@if($dataArchive=='fullhtml')
									@php $fullhtml='checked=checked'; @endphp
								@endif
							  @endforeach
							 
							<div class="form-check form-check-inline">
								<input name ="data_archive[]" class="form-check-input" type="checkbox" id="Screenshot" value="screenshot" {{$screenshot}}>
								<label class="form-check-label" for="Screenshot">{{ ucwords(trans('global.request.fields.screenshot')) }}</label>
							</div>

							<div class="form-check form-check-inline">
								<input name ="data_archive[]" class="form-check-input" type="checkbox" id="Full" value="fullhtml" {{$fullhtml}}>
								<label class="form-check-label" for="Full">{{ ucwords(trans('global.request.fields.fullpagehtml')) }}</label>
							</div>
							<div class="data_archive_error errors"> </div>
							</div>													
						</div>								
					<div class="form-row mt-4">
					<div class="col-md-12 offset-lg-3 offset-xl-2">
					<input id ="request_id" class="form-check-input" type="hidden" value="{{$request->id}}">
					<button type="submit" class="btn btn-primary default btn-lg mb-2 mb-sm-0 mr-2 col-12 col-sm-auto">Submit</button>
					<div class="spinner-border text-primary request_loader" style="display:none"></div>
					</div>	
					</div>
               </form>
		</div>
	</div>
</div>