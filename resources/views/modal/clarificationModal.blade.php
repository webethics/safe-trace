<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Ask For clarification</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
	<form action="{{ url('request/clarification') }}" method="POST" id="clarificationRequest" >
	 @csrf
	<div class="col">
	<textarea class="form-control" name="clarification" placeholder="" rows="3"></textarea>
	<div class="clarification_error errors"></div>
	<input type="hidden" name="request_id" id="request_id" value="{{$request_id}}"/>
	<input type="hidden" name="report_id" id="report_id" value="{{$report->id}}"/>
	<button type="submit" class="btn btn-primary default btn-lg mt-4">{{ trans('global.submit') }}</button>
	
	</div>
	</form>
		</div>
	</div>
</div>
