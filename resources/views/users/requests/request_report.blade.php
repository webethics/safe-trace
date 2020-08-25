@extends('layouts.admin')
@section('content')
@section('addEditRequestjs')
<script src="{{ asset('js/module/request.js')}}"></script>	
@stop
<div class="row">

   @php $cls='display:none'; $sm=12; @endphp
	@if(count($attachment)>0 && $request->status!=3)
		@php $cls='display:block'; $sm=9; @endphp
	 @endif	
	
<div class="col-sm-{{$sm}}" id="request_detail">

		<h1>Request Detail</h1>
	   </div>
	<!-- IF STATUS IN NOT COMPLETE AND REPORT UPLOADS BUT NOT COMPLETED SHOW COMPLETE BUTTON -->
	<div class="spinner-border text-primary search_spinloder" style="display:none"></div>
	<div class="col-sm-2 complete_button" style="{{$cls}}">
		<div class="form-group mb-4">
			<span class="badge custom-bandage badge-success">
			<a href="javascript:void(0);" data-id="{{$request->id}}" data-confirm_message ="Are you want to Complete the Request ?"  data-left_button_name ="Complete" data-left_button_id ="complete_report" data-left_button_cls="btn-primary"  id="open_confirmBox" style="color:#ffffff">Mark as Complete</a></span>
		</div>	
		
	</div>
		
   </div>
<div class="separator mb-5"></div>
<div class="row mb-4">
	<div class="col-12">
		<div class="row">
		<div class="col-md-6 mb-4">
		<div class="card h-100 case-reequest-box">
			<div class="card-body">
				<div class="row">
					<div class="form-group col-sm-12">
						<span class="badge custom-bandage badge-secondary">{{ trans('global.request.fields.case_number') }}: {{$request->case_number}}</span>
					</div>
					<div class="w-100"></div>
					<div class="col-sm-12">
					   <span class="badge custom-bandage badge-secondary">{{ trans('global.request.fields.requested_date')}}:
						{{\Carbon\Carbon::parse($request->created_at)->format('d-M-Y')}}</span>
					</div>
				</div>
			</div>
		</div>				
		</div>
		<div class="col-md-6 mb-4 ">
		<div class="card h-100">
			<div class="card-body">
				<div class="row">
					<div class="col-sm-6">
					  <div class="form-group mb-4">
						<span class="badge custom-bandage badge-success">{{ trans('global.request.fields.case_priority')}}: {{ucwords($request->priority)}} </span>
						</div>
					</div>
					<div class="col-sm-6">
					@php
						$data = get_request_status_name($request->status);
						$status= $data['status'];
						$cls= $data['cls'];
					@endphp
					<div class="form-group mb-4 ">
						<span class="badge custom-bandage badge-success request_status {{$cls}}">{{ trans('global.request.fields.case_status')}}: <span id="status_change"> {{$status}}</span></span>
					</div>
					</div>
					
				<div class="col-md-12">
				<span class="badge custom-bandage badge-secondary">{{ trans('global.request.fields.completion_date')}}: <span id="completed_at">
				@if($request->completed_at){{\Carbon\Carbon::parse($request->completed_at)->format('d-M-Y')}} 
				@else
					-
				@endif
				</span>
				</span>							
				</div>									
				</div>
			</div>
		</div>				
		</div>
		</div>	
		
		<div class="card mb-4">
			<div class="card-body">
				@php
				 $social = json_decode($request->social_media);
				@endphp			
				<h5 class="mb-4"><xmp>Requested Analysis Parameters:
				@if($request->name!='')
			   <Requested Names: {{$request->name}}>,
			   @endif
			   @if($request->company!='')
				<Requested Company Names: {{ucwords($request->company)}}>
			   @endif
			   @if($request->url!=''), <Requested URL's :{{$request->url}}> 
				@endif
				 @if($request->social_media!='')<Requested Social Media Accounts :
				 @foreach($social as $key => $socialname)
				  {{$key }}:{{$socialname}}|
				  @endforeach >
				  @endif</xmp></h5>
			
		<!--  COMMENT SECTION START FROM HERE -->	
			<h5 class="mb-4">Data Provider Comments on Case Reports:</h5>
			<div class="mt-5">
			    @php 
				$auto_height = ''; 
				$custom_comment_scrollbar = 'custom-comment-scrollbar'; 
				@endphp
			     @if(count($comments)>4)
					 @php 
					 $auto_height = 'height:400px;';
				     $custom_comment_scrollbar = 'custom-comment-scrollbar';
					 @endphp
				 @endif
				@if(count($comments)>0)
					<!--  IF USER ANALYST -->
				@if($roleIdArr['DATA_ANALYST']==current_user_role_id())
				 <div class="row" id="{{$custom_comment_scrollbar}}" style="{{$auto_height}} position: relative;">
					@foreach($comments as $key=>$comment)
					   <div class="w-100"></div>
					        <!-- GET MINUTES FROM -->
					        @php 
							$diff_in_minutes='';
							$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $comment->created_at);
							$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Carbon\Carbon::now());
							$diff_in_minutes = $to->diffInMinutes($from); 
							@endphp
					   
					   
					   @if($comment->reciever_id == user_id() )
						 <!-- If user recieve messages -->
						<div class="col-md-12 ml-auto">   
						<div class="py-3 px-4 mt-3 custom-comment bg-light recieve_msg" style="text-align:right">
						<p class="mb-1">{{$comment->comment}}</p>
						@if($comment->sender_id != user_id())
						@php 
						$sender_name = user_data_by_id($comment->sender_id)
						@endphp
						<p class="mb-1 user"><b>By: {{$sender_name->first_name }} {{$sender_name->last_name }}</b></p>
						@endif
						<p class="mb-1 time">{{ Carbon\Carbon::parse($comment->created_at)->diffForHumans()}}</p>
					
						</div>
						</div>
						
						<!-- If user send messages -->
						@elseif($comment->reciever_id != user_id())
						<div class="col-md-12" id="comment_box_{{ $comment->id }}">
							<div class="py-3 px-4 mt-3 custom-comment bg-light sender" >
							<p class="mb-1"  id="comment_{{ $comment->id }}">{{$comment->comment}}</p>
							<!-- IF USER -->
							@php $data_user = user_data_by_id($comment->sender_id)   @endphp
							@if($comment->sender_id == user_id() )
							@php 
							$sender_name = user_data_by_id($comment->sender_id)
							@endphp
							<p class="mb-1 user"><b>{{$sender_name->first_name }} {{$sender_name->last_name }}</b></p>
							@endif
							<!-- IF ADMIN COMMENT  -->
							@if($roleIdArr['DATA_ADMIN']==$data_user->role_id)
							@php 
							$sender_name = user_data_by_id($comment->sender_id)
							@endphp
							<p class="mb-1 user"><b>By: {{$sender_name->first_name }} {{$sender_name->last_name }}</b></p>
							@endif
							<!-- TIME AGO  -->
							<p class="mb-0 time">{{ Carbon\Carbon::parse($comment->created_at)->diffForHumans()}}
							
							</p>
							
							<!-- NOT COMPLETE AND ADMIN COMMENT NOT EDITABLE BY ANALYST -->
							@if($request->status!=3 && $roleIdArr['DATA_ADMIN']!=$data_user->role_id && 
							$diff_in_minutes <=10)
							<div class="icon-wrap">
							<a href="javascript:void(0)" data-comment_id="{{ $comment->id }}" class="openCommentModal" > <i class="glyph-icon simple-icon-note"></i></a>
							</div>
							@endif
							</div>
						</div>
						@endif
					@endforeach
				
				</div>
				@endif
				<!-- END ANALYST -->
				
				<!--  IF DATA ADMIN -->
				@if($roleIdArr['DATA_ADMIN']==current_user_role_id())
				<div class="row" id="{{$custom_comment_scrollbar}}" style="{{$auto_height}} position: relative;">
					@foreach($comments as $key=>$comment)
					
					     @php 
							$diff_in_minutes='';
							$to = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $comment->created_at);
							$from = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', Carbon\Carbon::now());
							$diff_in_minutes = $to->diffInMinutes($from); 
							@endphp
					    <div class="w-100"></div>
						<div class="col-md-12 ml-auto" id="comment_box_{{ $comment->id }}">   
						<div class="py-3 px-4 mt-3 custom-comment bg-light recieve_msg">
						<p class="mb-1" id="comment_{{ $comment->id }}">{{$comment->comment}}</p>
						@if($comment->sender_id != user_id())
						@php 
						$sender_name = user_data_by_id($comment->sender_id)
						@endphp
						
						<p class="mb-1 user"><b>By: {{$sender_name->first_name }} {{$sender_name->last_name }}</b></p>
						
						@endif
						
						@if($comment->sender_id == user_id())
						@php 
						$sender_name = user_data_by_id($comment->sender_id)
						@endphp
						
						<p class="mb-1 user"><b>{{$sender_name->first_name }} {{$sender_name->last_name }}</b></p>
						
						@endif
						<p class="mb-1 time">{{ Carbon\Carbon::parse($comment->created_at)->diffForHumans()}}</p>
				
						@if($comment->sender_id==user_id() && $diff_in_minutes<=10)
						<div class="icon-wrap">
						<a href="javascript:void(0)" data-comment_id="{{ $comment->id }}" class="openCommentModal" > <i class="glyph-icon simple-icon-note"></i></a>
						</div>
						@endif
						</div>
						</div>	
					@endforeach
				</div>
				@endif
				<!--  END DATA ADMIN -->
				@else
					<div  class="errors">No Report found </div>
				@endif
						
			
				</div>
			</div>
		</div>
	<!--  COMMENT SECTION START FROM HERE -->		
		
		<!--      Reports download -->
		
		<div class="card mb-4">
			<div class="card-body">
			<h5 class="mb-4">Downloads:</h5>
			<div class="table-responsive">
				<table class="table table-hover mb-0" id="attachment_file_liding">
					<thead class="bg-primary">
						<tr>
							<th scope="col">File Name</th>
							<th scope="col">File Type</th>
							<th scope="col">Report Time Stamp Time</th>
							
							<!-- IF USER IS DATA ANALYST AND STATUS IS NOT COMPLETE || USER IS DATA ADMIN SHOW ACTION FIELD  -->
							@if((get_request_status_number($request->id)!=3 &&  current_user_role_id()==$roleIdArr['DATA_ANALYST']) || current_user_role_id()==$roleIdArr['DATA_ADMIN'])
							<th id="actions" scope="col">Actions</th>	
							@endif
						</tr>
					</thead>
					<tbody>
					   @if(count($attachment)>0)
					   @foreach($attachment as $key=>$attached)
						<tr id="attachment_id_{{$attached->id}}">
							<td>{{ pathinfo($attached->original_name, PATHINFO_FILENAME) }}</td>
							<td>{{ pathinfo($attached->original_name, PATHINFO_EXTENSION) }}</td>
							<td>{{\Carbon\Carbon::parse($attached->created_at)->format('d M, Y H:i')}} 
							
							</td>
							
							
						<!-- IF USER IS DATA ANALYST AND STATUS IS NOT COMPLETE || USER IS DATA ADMIN SHOW DELETE  -->
							@if((get_request_status_number($request->id)!=3 &&  current_user_role_id()==$roleIdArr['DATA_ANALYST']) || current_user_role_id()==$roleIdArr['DATA_ADMIN'])
							
							<td ><a class="remove_column" id="open_confirmBox"
							data-confirm_message ="Are you want to Delete the file ?"  data-left_button_name ="Delete" data-left_button_cls="btn-danger" data-left_button_id ="delete_file"  data-id="{{$attached->id}}" href="javascript:void(0)"> <i class="glyph-icon simple-icon-trash"></i></a>
							<a report_id = "{{$attached->report_id}}" href="{{url('report/viewdownload/')}}/{{$attached->report_id}}/{{$attached->id}}" > <i class="glyph-icon simple-icon-cloud-download"></i></a>
							</td>
				
							@endif
							
							
						</tr>
						@endforeach
						@else
							<tr>
							<td colspan="4" class="errors" style="text-align:center">No Report found </td>
							
						</tr>
						@endif
					</tbody>
				</table>
			</div>
			<div class="w-100"></div>


			</div>
	</div>
	
	
	
	
	<!---   IF ANALYST REPLY THEN SHOW THIS FIEDLS --> 
		@if((get_request_status_number($request->id)!=3 &&  current_user_role_id()==$roleIdArr['DATA_ANALYST']) || current_user_role_id()==$roleIdArr['DATA_ADMIN'])
			 
			
	
		 
		 
		<form action="{{url('analyst/report')}}" method="POST" enctype="multipart/form-data" id="report_form"   >
	        @csrf
			<div class="card mb-4">
			<div class="card-body">	 
			<h5  class="mb-4">Upload Your Report:</h5>			 
			<div class="col-md-12 mb-4">
			<textarea class="form-control" id="comment"name="comment" placeholder="Enter your comment here" rows="3"></textarea>
			<div class="comment_error errors"></div>
			</div>
		    <input type="hidden" value="{{$request->id}}" name="request_id"  id="request_id" >	
			<div class="col-md-12">
				<div class="dropzone" id="dropzone">
				<div class="fallback">
							<input type="file" name="file" multiple>
				</div>
				</div>
			<div class="file_error errors"></div>	
			</div>
			
				  @php  $status_complete=''; $disabled='';	 @endphp
					 @if($request->status == 3)
					@php 
				      $status_complete = 'checked=checked';
				      $disabled = 'disabled=disabled'
				
				     @endphp
					 @endif
			       <!--div class="col-md-12">		 
					 <div class="config-notification">
						<div class="form-group mb-0">
							<label class="col-form-label">Status Complete</label>
							<div class="custom-switch  custom-switch-primary custom-switch-small">
								<input class="custom-switch-input request_status" name="request_status" id="switch" value="{{$request->status}}" type="checkbox" {{$status_complete}} {{$disabled}}>
								<label class="custom-switch-btn" for="switch"></label>
							</div>
						</div>									
					</div>	
					</div-->	
			
					<div class="col-12 text-right mt-3">
						<button type="button" id="submitAnalystReport" class="btn btn-primary default btn-lg">Submit</button>
					</div>
					<div class="spinner-border text-primary" style="display:none"></div>
			</div>
			</div>
			</div>
			</form>
        @endif		
</div>
<div class="modal fade modal-top confirmBoxCompleteModal"  tabindex="-1" role="dialog"  aria-hidden="true"></div>

<!--  FOR EDIT Comment -->
<div class="modal fade EditComment" tabindex="-1" role="dialog" aria-hidden="true"></div>
 
@endsection