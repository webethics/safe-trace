<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Modify Comment</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
	<form action="{{ url('request/modifyComment') }}" method="POST" id="updateComment" >
	 @csrf
	<div class="col">
	<textarea class="form-control" name="clarification" placeholder="" rows="3">{{$comment->comment}}</textarea>
	<div class="clarification_error errors"></div>
	<input type="hidden" name="comment_id" id="comment_id" value="{{$comment->id}}"/>
	<button type="submit" class="btn btn-primary default btn-lg mt-4">{{ trans('global.submit') }}</button>
	<div class="spinner-border text-primary request_loader" style="display:none"></div>
	</div>
	</form>
		</div>
	</div>
</div>
