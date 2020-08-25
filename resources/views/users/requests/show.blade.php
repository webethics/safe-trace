@extends('layouts.admin')
@section('content')
@section('addEditRequestjs')
<script src="{{ asset('js/module/request.js')}}"></script>	
@stop
<div class="row">
	<div class="col-12">
		<h1>Request Detail</h1>
		<div class="separator mb-5"></div>
	</div>
</div>
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
				<span class="badge custom-bandage badge-secondary">{{ trans('global.request.fields.completion_date')}}:<span id="completed_at">
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
				<div class="row" id="{{$custom_comment_scrollbar}}" style="{{$auto_height}} position: relative;">
					@foreach($comments as $key=>$comment)
					   <div class="w-100"></div>
					   @if($comment->reciever_id == user_id() && ($comment->request_status!=4 &&$comment->request_status!=2))
						<div class="col-md-12 ml-auto">   
						<div class="py-3 px-4 mt-3 custom-comment bg-light recieve_msg" style="text-align:right">
						<p class="mb-1">{{$comment->comment}}</p>
						@if($comment->sender_id != user_id())
						@php 
						$sender_name = user_data_by_id($comment->sender_id)
						@endphp
						<p class="mb-1 user"><b>By:{{$sender_name->first_name }} {{$sender_name->last_name }}</b></p>
						@endif
						<p class="mb-1 time">{{ Carbon\Carbon::parse($comment->created_at)->diffForHumans()}}</p>
						</div>
						</div>
						
						<!-- If user send messages -->
						@elseif($comment->reciever_id != user_id())
						<div class="col-md-12">
						<div class="py-3 px-4 mt-3 custom-comment bg-light sender" >
						<p class="mb-1">{{$comment->comment}}</p>
						@if($comment->sender_id == user_id())
						@php 
						$sender_name = user_data_by_id($comment->sender_id)
						@endphp
						<p class="mb-1 user"><b>{{$sender_name->first_name }} {{$sender_name->last_name }}</b></p>
						@endif
						<p class="mb-0 time">{{ Carbon\Carbon::parse($comment->created_at)->diffForHumans()}}</p>
						</div>
						</div>
						@endif
					@endforeach
				</div>
				</div>
			</div>
		</div>
		
		
		<!--      Reports download -->
		
		<div class="card mb-4">
			<div class="card-body">
			<h5 class="mb-4">Downloads:</h5>
		@if($request->status == 3)
			<div class="table-responsive">
				<table class="table table-hover mb-0">
					<thead class="bg-primary">
						<tr>
							<th scope="col">File Name</th>
							<th scope="col">File Type</th>
							<th scope="col">Report Time Stamp Time</th>
							<th scope="col">Actions</th>										
						</tr>
					</thead>
					<tbody>
					   @if(count($reports)>0)
					   @foreach($reports as $key=>$report)
						<tr>
							<td>{{ pathinfo($report->zip_file_name, PATHINFO_FILENAME) }}</td>
							<td>{{ pathinfo($report->zip_file_name, PATHINFO_EXTENSION) }}</td>
							<td>{{ $report->created_at }}</td>
							<td><a href="{{url('report/download/')}}/{{$report->id}}"><i class="glyph-icon simple-icon-cloud-download"></i>

</a></td>
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
			@else 
			  <div class="errors" style="text-align:center">No Report found</div>
			@endif	
			<div class="w-100"></div>
	
			@if(get_request_status_number($request->id)==3)
			<div class="col-12 text-right mt-4" id="clarificationBox"><a a href="javascript:void(0)"  data-request_id="{{$request->id}}" class="btn btn-warning default btn-lg clarificationModalOpen">REQUEST ADDITIONAL INFORMATION</a></div>
		    @endif

			</div>
	</div>
</div>

 <div class="modal fade clarificationModal" tabindex="-1" role="dialog" aria-hidden="true"></div>



@endsection

