
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header py-1">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
					<p>@if($data_result['eventlogs']['event_title'])<strong>Event Title :</strong> {{$data_result['eventlogs']['event_title']}} @endif</p>
					<p>@if($data_result['username'])<strong>Username :</strong> {{$data_result['username']}} @endif</p>
					
					<p>@if($data_result['request_id'])<strong> Case Number  :</strong> {{$data_result['request_id']}} @endif</p>
					<p>@if($data_result['comment'])<strong>Comment :</strong> {{$data_result['comment']}} @endif</p>
					<p>@if($data_result['ipaddress'])<strong>IP Address : </strong>{{$data_result['ipaddress']}} @endif</p>
					<p>@if($data_result['filename'])<strong>Filename : </strong> 
					<br>@foreach($data_result['filename'] as $key=>$value)
						{{$value}} <br>
					@endforeach


					@endif</p>
					<p>@if($data_result['changed_fields'])<strong>Changed Fields : </strong> <br>@foreach($data_result['changed_fields'] as $key=>$value)
						{{$key}} : {{$value}} <br>
					@endforeach


					@endif</p>
					
					<p>@if($data_result['attempted_password'])<strong>Password : </strong>{{$data_result['attempted_password']}} @endif</p>
					<p>@if($data_result['created_at'])<strong>Time :</strong> {{$data_result['created_at']}} @endif</p>
					<a class="btn btn-dark btn-lg default  mb-1" data-dismiss="modal" aria-label="Close" href="#">Close</a>
					</div>
				</div>
</div>
          