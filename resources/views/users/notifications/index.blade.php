@extends('layouts.admin')
@section('content')

 <div class="row">
                <div class="col-12">
                    <h1>Notifications</h1>
                    <div class="separator mb-5"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12 mb-4">
						<table class="table table-hover mb-0">
							<thead class="bg-primary">
								<tr>
									<th scope="col">Case Number</th>
									<th scope="col">Message</th>
									<th scope="col">Send By</th>
									<th scope="col">Created On</th>
								</tr>
							</thead>
							<tbody>
							
							
							
							 @if(!empty($notifications) && $notifications->count())
								@foreach($notifications as $key => $notification)
									<tr>
										<td>{{$notification->requests->case_number}}</td>
										<td>{{ str_replace('[case_number]',$notification->requests->case_number,$notification->notificationMessage->notification_msg)}}</td>
										<td>{{$notification->sender->first_name}} {{$notification->sender->last_name}}</td>
										<td>{{$notification->created_at}}</td>
									</tr>
								
								@endforeach
							 @else
							<tr><td colspan="5" class="error" style="text-align:center">No Data Found.</td></tr>
							 @endif	
								
							</tbody>
</table>

                </div>
            </div>
	
   
@section('addEditRequestjs')
<script src="{{ asset('js/module/request.js')}}"></script>	
@stop
@endsection