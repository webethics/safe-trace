<table class="table table-hover mb-0">
		<thead class="bg-primary">
			<tr>
				<th scope="col">Username</th>
				<!--th scope="col">Event Name</th-->
				<th scope="col">Audited Events</th>
				<th scope="col">Time Stamp</th>
				<th scope="col">Actions</th>										
			</tr>
		</thead>
		<tbody>
		 @if(is_object($audit_logs) && !empty($audit_logs) && $audit_logs->count())
		 @foreach($audit_logs as $key => $auditlog) 
			<tr>
				<td>{{ $auditlog->username }}</td>
				<!--td>{{ $auditlog->eventlogs->event_name }}</td-->
				<td>{{ $auditlog->eventlogs->event_title }} </td>
				<td>{{\Carbon\Carbon::parse($auditlog->created_at)->format('d-M-Y H:i')}}</td>
				<td><a href="javascript:void(0)" class="auditModalOpen" data-audit_id="{{$auditlog->id}}" >View Details</a></td>
			</tr>
			
	    @endforeach
		 @else
		<tr><td colspan="4" class="error" style="text-align:center">No Data Found.</td></tr>
		 @endif									
		</tbody>
	</table>
	<!------------ Pagination -------------->
	@if(is_object($audit_logs) && !empty($audit_logs) && $audit_logs->count()) 
	 {!! $audit_logs->render() !!}  
	@endif	